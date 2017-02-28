<?php

require_once 'AppConfig.php';

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;

class DiConfig
{
    public static function RegisterDependencies() : \Phalcon\DiInterface
    {
        /**
         * The FactoryDefault Dependency Injector automatically registers
         * the services that provide a full stack framework.
         */
        $di = new FactoryDefault();

        /**
         * Shared configuration service
         */
        $di->setShared('config', function () {
            return AppConfig::GetConfig();
        });

        /**
         * The URL component is used to generate all kind of urls in the application
         */
        $di->setShared('url', function () use($di) {
            $config = $di->getShared("config");

            $url = new UrlResolver();
            $url->setBaseUri($config->application->baseUri);

            return $url;
        });

        /**
         * Setting up the view component
         */
        $di->setShared('view', function () use($di) {
            return new View();
        });

        /**
         * Database connection is created based in the parameters defined in the configuration file
         */
        $di->setShared('db', function () use($di) {
            $config = $di->getShared("config");

            $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
            $params = [
                'host'     => $config->database->host,
                'username' => $config->database->username,
                'password' => $config->database->password,
                'dbname'   => $config->database->dbname,
                'charset'  => $config->database->charset
            ];

            if ($config->database->adapter == 'Postgresql') {
                unset($params['charset']);
            }

            $connection = new $class($params);

            return $connection;
        });

        /**
         * If the configuration specify the use of metadata adapter use it or use memory otherwise
         */
        $di->setShared('modelsMetadata', function () {
            return new MetaDataAdapter();
        });

        return $di;
    }
}