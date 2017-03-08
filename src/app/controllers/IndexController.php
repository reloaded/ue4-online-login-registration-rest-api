<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 2/28/2017 4:15 PM
 */

namespace Reloaded\UnrealEngine4\Web\Controllers;

use App\Library\Net\HttpStatusCode;
use App\Library\Responses\FaultResponse;
use Phalcon\Http\ResponseInterface;

class IndexController extends ControllerBase
{

    public function routeNotFoundAction() : ResponseInterface
    {
        return $this->response
            ->setStatusCode(HttpStatusCode::NotFound)
            ->setJsonContent(
                new FaultResponse('Endpoint not found.', HttpStatusCode::NotFound)
            );
    }

    public function unauthorizedAction() : ResponseInterface
    {
        return $this->response
            ->setStatusCode(HttpStatusCode::Unauthorized)
            ->setJsonContent(
                new FaultResponse('Unauthorized. Please login.', HttpStatusCode::Unauthorized)
            );
    }

}

