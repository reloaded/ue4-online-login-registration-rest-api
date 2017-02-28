<?php

namespace Reloaded\UnrealEngine4\Web\Config;

class AppConfig
{
    public static function GetConfig()
    {
        return include __DIR__ . "/config.php";
    }
}