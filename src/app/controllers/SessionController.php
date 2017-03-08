<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 2/28/2017 4:15 PM
 */

namespace Reloaded\UnrealEngine4\Web\Controllers;


use App\Library\Net\HttpStatusCode;
use App\Library\Requests\Account\Login as LoginRequest;
use App\Library\Requests\Account\Validation\Login as LoginRequestValidation;
use App\Library\Responses\DataObjectResponse;
use App\Library\Responses\FaultResponse;
use App\Library\Responses\ValidationFaultResponse;
use App\Library\Responses\ValidationFieldError;
use App\Models\Players;
use App\Models\PlayerSessions;
use Ramsey\Uuid\Uuid;

class SessionController extends ControllerBase
{
    #region API Endpoints

    /**
     * Authenticates an existing player and create a new session. If there is an existing session for the player its
     * invalidated and a new one is created.
     *
     * @param LoginRequest $request
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function loginAction(LoginRequest $request)
    {
        try
        {
            $this->db->begin();

            $requestValidation = new LoginRequestValidation();
            $requestErrors = $requestValidation->validate(null, $request);

            if(count($requestErrors))
            {
                $validationFault = new ValidationFaultResponse(HttpStatusCode::BadRequest);
                $validationFault->addPhalconValidationGroup($requestValidation->getMessages());

                return $this->response
                    ->setStatusCode(HttpStatusCode::BadRequest)
                    ->setJsonContent($validationFault);
            }

            #region Lookup player by email and password

            $player = Players::findFirst([
                'conditions' => 'Email = ?1',
                'bind' => [
                    1 => $request->Email
                ]
            ]);

            if(!$player || !password_verify($request->Password, $player->getPassword()))
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

            #endregion

            #region Check player activated

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

            #endregion

            #region Create player session

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

            #endregion

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

    #endregion
}

