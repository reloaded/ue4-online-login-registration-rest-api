<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 2/28/2017 4:15 PM
 */

namespace Reloaded\UnrealEngine4\Web\Config;

class AppConfig
{
    public static function GetConfig()
    {
        return include __DIR__ . "/config.php";
    }
}