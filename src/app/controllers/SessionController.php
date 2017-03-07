<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 2/28/2017 4:15 PM
 */

namespace Reloaded\UnrealEngine4\Web\Controllers;

use App\Library\EmailMessages\AccountRecovery\ActivateTemplate;
use App\Library\Net\HttpStatusCode;
use App\Library\Net\Responses\DataObjectResponse;
use App\Library\Net\Responses\FaultResponse;
use App\Library\Net\Responses\ValidationFaultResponse;
use App\Library\Net\Responses\ValidationFieldError;
use App\Library\Requests\Login as LoginRequest;
use App\Library\Requests\Registration as RegistrationRequest;
use App\Library\Requests\Validation\Login;
use App\Library\Requests\Validation\Registration as RegistrationRequestValidation;
use App\Models\PlayerAccountRecovery;
use App\Models\Players;
use App\Models\PlayerSessions;
use Ramsey\Uuid\Uuid;
use Zend\Mail\Message;
use Zend\Math\Rand;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Mime;
use Zend\Mime\Part;

class SessionController extends ControllerBase
{
    /**
     * Registers a new player account.
     *
     * Takes a JSON structure from the HTTP request body, decodes it as a RegistrationRequest object and creates a
     * new player if the request is valid. An account activation email will be sent to the player's email address
     * immediately.
     *
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     * @see RegistrationRequest
     */
    public function registerAction()
    {
        try
        {
            $this->db->begin();

            /** @var $registrationRequest RegistrationRequest */
            $registrationRequest = $this->_mapper->map($this->request->getJsonRawBody(), new RegistrationRequest());

            $requestValidation = new RegistrationRequestValidation();
            $requestErrors = $requestValidation->validate(null, $registrationRequest);

            if(count($requestErrors))
            {
                $validationFault = new ValidationFaultResponse(HttpStatusCode::BadRequest);
                $validationFault->addPhalconValidationGroup($requestValidation->getMessages());

                return $this->response
                    ->setStatusCode(HttpStatusCode::BadRequest)
                    ->setJsonContent($validationFault);
            }

            #region Create Player

            $guid = Uuid::uuid4();

            $player = new Players([
                'Id' => $guid->toString(),
                'FirstName' => $registrationRequest->FirstName,
                'LastName' => $registrationRequest->LastName,
                'Email' => $registrationRequest->Email,
                'InGameName' => $registrationRequest->InGameName,
                'Password' => password_hash($registrationRequest->Password, PASSWORD_DEFAULT)
            ]);

            if(!$player->save())
            {
                $this->db->rollback();

                $validationFault = new ValidationFaultResponse(HttpStatusCode::BadRequest);
                $validationFault->addPhalconModelMessages($player->getMessages());

                return $this->response
                    ->setStatusCode(HttpStatusCode::BadRequest)
                    ->setJsonContent($validationFault);
            }

            #endregion

            #region Create Account Recovery

            $accountRecoveryDuration = new \DateTime($this->_appSettings->accountRecoveryDuration);
            $accountRecovery = new PlayerAccountRecovery([
                'PlayerId' => $player->getId(),
                'Code' => Rand::getString(10),
                'Expiration' => $accountRecoveryDuration->format('Y-m-d H:i:s'),
                'GeneratedOn' => (new \DateTime())->format('Y-m-d H:i:s'),
                'Type' => 'Activation'
            ]);

            if(!$accountRecovery->save())
            {
                $this->db->rollback();

                $faultResponse = new FaultResponse(
                    'There was an error with account activation.',
                    HttpStatusCode::InternalServerError
                );

                return $this->response
                    ->setStatusCode(HttpStatusCode::InternalServerError)
                    ->setJsonContent($faultResponse);
            }

            #endregion

            $this->db->commit();

            #region Send Account Activation Email

            $this->_mailTransport->send($this->_createAccountActivationMessage($player, $accountRecovery));

            #endregion

            $responseData = (object) [
                'Id' => $player->getId(),
                'FirstName' => $registrationRequest->FirstName,
                'LastName' => $registrationRequest->LastName,
                'Email' => $registrationRequest->Email,
                'InGameName' => $registrationRequest->InGameName
            ];

            return $this->response
                ->setStatusCode(HttpStatusCode::Created)
                ->setJsonContent(new DataObjectResponse($responseData, HttpStatusCode::Created));
        }
        catch(\Exception $ex)
        {
            if($this->db->isUnderTransaction())
            {
                $this->db->rollback();
            }

            return $this->response
                ->setStatusCode(HttpStatusCode::InternalServerError)
                ->setJsonContent(new FaultResponse(
                    'There was an unexpected error while registering your account.',
                    HttpStatusCode::InternalServerError
                ));
        }
    }

    /**
     * Authenticates an existing player and create a new session. If there is an existing session for the player its
     * invalidated and a new one is created.
     *
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function loginAction()
    {
        try
        {
            $this->db->begin();

            /** @var $loginRequest LoginRequest */
            $loginRequest = $this->_mapper->map($this->request->getJsonRawBody(), new LoginRequest());

            $requestValidation = new Login();
            $requestErrors = $requestValidation->validate(null, $loginRequest);

            if(count($requestErrors))
            {
                $validationFault = new ValidationFaultResponse(HttpStatusCode::BadRequest);
                $validationFault->addPhalconValidationGroup($requestValidation->getMessages());

                return $this->response
                    ->setStatusCode(HttpStatusCode::BadRequest)
                    ->setJsonContent($validationFault);
            }

            $player = Players::findFirst([
                'conditions' => 'Email = ?1',
                'bind' => [
                    1 => $loginRequest->Email
                ]
            ]);

            if(!$player || !password_verify($loginRequest->Password, $player->getPassword()))
            {
                $validationFault = new ValidationFaultResponse(HttpStatusCode::BadRequest);
                $validationFault->addValidationErrors([new ValidationFieldError(
                    'Email_Or_Password',
                    'The email and password you entered does not match our records.'
                )]);

                return $this->response
                    ->setStatusCode(HttpStatusCode::BadRequest)
                    ->setJsonContent($validationFault);
            }

            if(!$player->getIsActivated())
            {
                $faultResponse = new FaultResponse(
                    'You must activate your account before logging in.',
                    HttpStatusCode::Unauthorized
                );

                return $this->response
                    ->setStatusCode(HttpStatusCode::Unauthorized)
                    ->setJsonContent($faultResponse);
            }

            $playerSession = PlayerSessions::findFirst([
                'conditions' => 'PlayerId = ?1',
                'bind' => [
                    1 => Uuid::fromString($player->getId())->getBytes()
                ]
            ]);

            if(!$playerSession)
            {
                $playerSession = new PlayerSessions([
                    'PlayerId' => $player->getId()
                ]);
            }

            $sessionExpiration = new \DateTime($this->di->getShared('config')->application->sessionDuration);

            $playerSession
                ->setSessionId(Uuid::uuid4()->toString())
                ->setExpiration($sessionExpiration->format('Y-m-d H:i:s'))
                ->setCreated((new \DateTime())->format('Y-m-d H:i:s'))
                ->setRemoteIp($this->request->getClientAddress());


            if(!$playerSession->save())
            {
                $this->db->rollback();

                $validationFault = new ValidationFaultResponse(HttpStatusCode::BadRequest);
                $validationFault->addPhalconModelMessages($playerSession->getMessages());

                return $this->response
                    ->setStatusCode(HttpStatusCode::BadRequest)
                    ->setJsonContent($validationFault);
            }

            $this->db->commit();

            $responseData = (object) [
                'SessionId' => $playerSession->getSessionId(),
                'Expiration' => $playerSession->getExpiration()
            ];

            return $this->response
                ->setStatusCode(HttpStatusCode::Created)
                ->setJsonContent(new DataObjectResponse($responseData, HttpStatusCode::Created));
        }
        catch(\Exception $ex)
        {
            if($this->db->isUnderTransaction())
            {
                $this->db->rollback();
            }

            return $this->response
                ->setStatusCode(HttpStatusCode::InternalServerError)
                ->setJsonContent(new FaultResponse(
                    'There was an unexpected error while authenticating.',
                    HttpStatusCode::InternalServerError
                ));
        }
    }

    /**
     * Deletes a player's session.
     *
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function logoutAction()
    {
        try
        {
            $sessionId = Uuid::fromString($this->dispatcher->getParam('sessionId'));

            $session = PlayerSessions::findFirst([
                'conditions' => 'SessionId = ?1',
                'bind' => [
                    1 => $sessionId->getBytes()
                ]
            ]);

            if($session)
            {
                $this->db->begin();

                if(!$session->delete())
                {
                    $this->db->rollback();

                    $fault = new FaultResponse('There was an error while attempting to log out.', HttpStatusCode::InternalServerError);

                    return $this->response
                        ->setStatusCode(HttpStatusCode::InternalServerError)
                        ->setJsonContent($fault);
                }

                $this->db->commit();
            }

            return $this->response
                ->setStatusCode(HttpStatusCode::OK)
                ->setJsonContent(new DataObjectResponse(null, HttpStatusCode::OK));
        }
        catch(\Exception $ex)
        {
            if($this->db->isUnderTransaction())
            {
                $this->db->rollback();
            }

            return $this->response
                ->setStatusCode(HttpStatusCode::InternalServerError)
                ->setJsonContent(new FaultResponse(
                    'There was an unexpected error while authenticating.',
                    HttpStatusCode::InternalServerError
                ));
        }
    }

    /**
     * Extends the expiration of a player's session.
     *
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function heartbeatAction()
    {
        try
        {
            $this->db->begin();

            $sessionId = Uuid::fromString($this->dispatcher->getParam('sessionId'));

            $session = PlayerSessions::findFirst([
                'conditions' => 'SessionId = ?1',
                'bind' => [
                    1 => $sessionId->getBytes()
                ]
            ]);

            if(!$session)
            {
                $this->db->rollback();

                return $this->response
                    ->setStatusCode(HttpStatusCode::NotFound)
                    ->setJsonContent(new FaultResponse(
                        'Session not found.',
                        HttpStatusCode::NotFound
                    ));
            }

            $sessionExpiration = new \DateTime($this->di->getShared('config')->application->sessionDuration);

            $updates = [
                'Expiration' => $sessionExpiration->format('Y-m-d H:i:s')
            ];

            if(!$session->save($updates))
            {
                $this->db->rollback();

                $fault = new FaultResponse('There was an error while attempting to extend the session.', HttpStatusCode::InternalServerError);

                return $this->response
                    ->setStatusCode(HttpStatusCode::InternalServerError)
                    ->setJsonContent($fault);
            }

            $this->db->commit();

            $responseData = (object) [
                'SessionId' => $session->getSessionId(),
                'Expiration' => $session->getExpiration()
            ];

            return $this->response
                ->setStatusCode(HttpStatusCode::OK)
                ->setJsonContent(new DataObjectResponse($responseData, HttpStatusCode::OK));
        }
        catch(\Exception $ex)
        {
            if($this->db->isUnderTransaction())
            {
                $this->db->rollback();
            }

            return $this->response
                ->setStatusCode(HttpStatusCode::InternalServerError)
                ->setJsonContent(new FaultResponse(
                    'There was an unexpected error while authenticating.',
                    HttpStatusCode::InternalServerError
                ));
        }
    }

    /**
     * Creates a new \Zend\Mail\Message for the account activation email.
     *
     * @param Players $player
     * @param PlayerAccountRecovery $accountRecovery
     * @return Message
     */
    private function _createAccountActivationMessage(Players $player, PlayerAccountRecovery $accountRecovery): Message
    {
        $emailTemplate = new ActivateTemplate($player, $accountRecovery);

        $html = new Part($emailTemplate->renderHtml());
        $html->type = Mime::TYPE_HTML;
        $html->charset = 'utf-8';
        $html->encoding = Mime::ENCODING_QUOTEDPRINTABLE;

        $body = new MimeMessage();
        $body->setParts([$html]);

        $activationMessage = new Message();
        $activationMessage
            ->addTo($player->getEmail())
            ->addFrom($this->di->getShared('config')->application->noReplyEmail)
            ->setSubject(sprintf(
                'Your new account with %s',
                $this->di->getShared('config')->application->siteName
            ))
            ->setBody($body);

        return $activationMessage;

    }
}

