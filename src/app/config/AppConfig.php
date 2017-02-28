<?php

class AppConfig
{
    public static function GetConfig()
    {
        return include __DIR__ . "/config.php";
    }
}