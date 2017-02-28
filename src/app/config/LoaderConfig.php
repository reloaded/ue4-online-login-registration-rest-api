<?php

class LoaderConfig
{
    public static function RegisterLoader(\Phalcon\DiInterface $di)
    {
        $loader = new \Phalcon\Loader();

        /**
         * We're a registering a set of directories taken from the configuration file
         */
        $loader->registerDirs(
            [
                $di->getShared("config")->application->controllersDir,
                $di->getShared("config")->application->modelsDir
            ]
        )->register();

    }
}