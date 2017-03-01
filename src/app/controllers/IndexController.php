<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 2/28/2017 4:15 PM
 */

namespace Reloaded\UnrealEngine4\Web\Controllers;

class IndexController extends ControllerBase
{

    public function route404Action()
    {
        return json_encode(new class {
            public $Errors = [];
            public $Success = false;
        });
    }

}

