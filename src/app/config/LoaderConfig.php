<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 2/28/2017 4:15 PM
 */

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
                'Reloaded\\UnrealEngine4\\Models' => $di->getShared("config")->application->modelsDir,
                'Reloaded\\UnrealEngine4\\Library' => $di->getShared("config")->application->libraryDir

            ]
        )->register();

    }
}