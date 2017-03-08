<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 2/28/2017 4:15 PM
 */

namespace Reloaded\UnrealEngine4\Web\Controllers;

use App\Library\Net\HttpStatusCode;
use App\Library\Responses\DataObjectResponse;
use App\Library\Responses\FaultResponse;
use App\Models\PlayerSessions;
use Ramsey\Uuid\Uuid;

class SessionController extends ControllerBase
{
    #region API Endpoints

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

