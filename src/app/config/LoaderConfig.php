<?php

namespace Reloaded\UnrealEngine4\Web\Config;

use Phalcon\DiInterface;
use Phalcon\Loader;

class LoaderConfig
{
    public static function RegisterLoader(DiInterface $di)
    {
        $loader = new Loader();

        /**
         * We're a registering a set of directories taken from the configuration file
         */
        $loader->registerNamespaces(
            [
                'Reloaded\\UnrealEngine4\\Web\\Controllers' => $di->getShared("config")->application->controllersDir,
                'Reloaded\\UnrealEngine4\\Web\\Models' => $di->getShared("config")->application->modelsDir

            ]
        )->register();

    }
}