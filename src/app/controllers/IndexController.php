<?php

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

