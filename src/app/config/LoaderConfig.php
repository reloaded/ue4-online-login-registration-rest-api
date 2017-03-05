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
    /**
     * Configures PHP autoloading that locates PHP classes automatically therefore removing your need to require or
     * include class files yourself.
     *
     * @param DiInterface $di Phalcon Dependency Injection service.
     * @see https://docs.phalconphp.com/en/latest/reference/loader.html
     *
     */
    public static function registerLoader(DiInterface $di)
    {
        $loader = new Loader();

        /**
         * Register our Phalcon classes and namespaces with the Phalcon loader
         */
        $loader->registerNamespaces(
            [
                'Reloaded\\UnrealEngine4\\Web\\Controllers' => $di->getShared("config")->application->controllersDir,
                'App\\Models' => $di->getShared("config")->application->modelsDir,
                'App\\Library' => $di->getShared("config")->application->libraryDir,
                'App\\Validation' => $di->getShared("config")->application->validationDir

            ]
        )->register();

        /**
         * Require the Composer vendor autoloader file as well so Composer's autoload can load our vendor libraries
         */

        /** @noinspection PhpIncludeInspection */
        require_once $di->getShared('config')->application->vendorDir . 'autoload.php';
    }
}