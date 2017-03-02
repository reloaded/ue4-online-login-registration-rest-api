<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 2/28/2017 4:15 PM
 */

namespace Reloaded\UnrealEngine4\Web\Config;

require_once 'AppConfig.php';

use Phalcon\Di\FactoryDefault;
use Phalcon\DiInterface;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;

class DiConfig
{
    /**
     * Configure dependency injection of external services.
     *
     * @return DiInterface
     * @see https://docs.phalconphp.com/en/latest/reference/di.html
     */
    public static function registerDependencies() : DiInterface
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
         *
         * @note We don't use Views because this is a REST API, but Phalcon requires this DI key to
         * be setup and return a View object. So we return a newly empty constructed View.
         */
        $di->setShared('view', function () use($di) {
            return new View();
        });

        /**
         * Database connection is created based on the parameters defined in the configuration file
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
         * Specify the metadata adapter to use to speed up looking up model metadata.
         *
         * @see https://docs.phalconphp.com/en/latest/reference/models-metadata.html
         */
        $di->setShared('modelsMetadata', function () {
            return new MetaDataAdapter();
        });

        $di->set('dispatcher', function () {
            $dispatcher = new Dispatcher();

            $dispatcher->setDefaultNamespace(
                'Reloaded\\UnrealEngine4\\Web\\Controllers'
            );

            return $dispatcher;
        });

        $di->setShared('jsonMapper', function() {
            return new \JsonMapper();
        });

        return $di;
    }
}