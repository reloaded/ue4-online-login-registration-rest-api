<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 2/28/2017 4:15 PM
 */

namespace Reloaded\UnrealEngine4\Web\Controllers;

use Phalcon\Http\ResponseInterface;
use Reloaded\UnrealEngine4\Library\Net\HttpStatusCode;
use Reloaded\UnrealEngine4\Library\Responses\FaultResponse;

class IndexController extends ControllerBase
{

    public function route404Action() : ResponseInterface
    {
        return $this->response->setJsonContent(
            new FaultResponse('Endpoint not found.', HttpStatusCode::NotFound)
        );
    }

}

