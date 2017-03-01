<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 2/28/2017 4:15 PM
 */

namespace Reloaded\UnrealEngine4\Web\Controllers;

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
    /** @var \JsonMapper */
    protected $mapper;

    public function initialize()
    {
        $this->mapper = $this->di->getShared('jsonMapper');
    }
}
