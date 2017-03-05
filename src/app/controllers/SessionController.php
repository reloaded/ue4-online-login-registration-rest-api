<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 2/28/2017 4:15 PM
 */

namespace Reloaded\UnrealEngine4\Web\Controllers;

use App\Library\Net\HttpStatusCode;
use App\Library\Net\Responses\DataObjectResponse;
use App\Library\Net\Responses\FaultResponse;
use App\Library\Net\Responses\ValidationFaultResponse;
use App\Library\Requests\RegistrationRequest;
use App\Library\Requests\RegistrationRequestValidator;
use Ramsey\Uuid\Uuid;
use Reloaded\UnrealEngine4\Models\Players;

class SessionController extends ControllerBase
{
    /**
     * Registers a new player account.
     *
     * Takes a JSON structure from the HTTP request body, decodes it as a RegistrationRequest object and creates a
     * new player if the request is valid.
     *
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     * @see LoginRequest
     */
    public function registerAction()
    {
        try
        {
            $this->db->begin();

            /** @var $registrationRequest RegistrationRequest */
            $registrationRequest = $this->mapper->map($this->request->getJsonRawBody(), new RegistrationRequest());

            $requestValidation = new RegistrationRequestValidator();
            $requestErrors = $requestValidation->validate(null, $registrationRequest);

            if(count($requestErrors))
            {
                $validationFault = new ValidationFaultResponse(HttpStatusCode::BadRequest);
                $validationFault->addPhalconValidationGroup($requestValidation->getMessages());

                return $this->response
                    ->setStatusCode(HttpStatusCode::BadRequest)
                    ->setJsonContent($validationFault);
            }

            $guid = Uuid::uuid4();

            $player = new Players([
                "Id" => $guid->toString(),
                "FirstName" => $registrationRequest->FirstName,
                "LastName" => $registrationRequest->LastName,
                "Email" => $registrationRequest->Email,
                "InGameName" => $registrationRequest->InGameName,
                "Password" => password_hash($registrationRequest->Password, PASSWORD_DEFAULT)
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

            $this->db->commit();

            $responseData = (object) [
                "Id" => $player->getId(),
                "FirstName" => $registrationRequest->FirstName,
                "LastName" => $registrationRequest->LastName,
                "Email" => $registrationRequest->Email,
                "InGameName" => $registrationRequest->InGameName
            ];

            return $this->response
                ->setStatusCode(HttpStatusCode::Created)
                ->setJsonContent(new DataObjectResponse($responseData, HttpStatusCode::Created));
        }
        catch(\Exception $ex)
        {
            $this->db->rollback();

            return $this->response
                ->setStatusCode(HttpStatusCode::InternalServerError)
                ->setJsonContent(new FaultResponse(
                    'There was an unexpected error while registering your account.',
                    HttpStatusCode::InternalServerError
                ));
        }
    }

    public function loginAction()
    {

    }

    public function logoutAction()
    {

    }
}

