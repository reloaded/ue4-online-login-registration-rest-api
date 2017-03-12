<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 2/28/2017 4:15 PM
 */

namespace Reloaded\UnrealEngine4\Web\Config;

require_once 'AppConfig.php';

use App\Library\Requests\AbstractRequest;
use App\Plugins\AuthenticationAnnontation;
use Phalcon\Di\FactoryDefault;
use Phalcon\DiInterface;
use Phalcon\Events\Event;
use Phalcon\Events\Manager;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use ReflectionMethod;
use Zend\Mail\Transport\File as FileTransport;
use Zend\Mail\Transport\FileOptions;
use Zend\Math\Rand;

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

            /** @var \Phalcon\Db\Adapter $connection */
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

        $di->set('dispatcher', function () use($di) {
            // Create an EventsManager
            $eventsManager = new Manager();

            $eventsManager->attach(
                "dispatch:beforeDispatchLoop",
                function (Event $event, $dispatcher) use($di)
                {
                    /** @var Dispatcher $dispatcher */

                    // Possible controller class name
                    $controllerName = $dispatcher->getControllerClass();

                    // Possible method name
                    $actionName = $dispatcher->getActiveMethod();

                    try
                    {
                        // Get the reflection for the method to be executed
                        $reflection = new ReflectionMethod($controllerName, $actionName);

                        $parameters = $reflection->getParameters();

                        for($i = 0; $i < count($parameters); $i++)
                        {
                            #region Map JSON request body to a custom request object if applicable

                            // Get the expected request class name
                            $className = $parameters[$i]->getClass()->name;

                            // Check if the parameter expects a request class instance
                            if (is_subclass_of($className,  AbstractRequest::class))
                            {
                                $jsonMapper = $di->getShared('jsonMapper');

                                try
                                {
                                    $requestObject = $jsonMapper->map(
                                        $di->getShared('request')->getJsonRawBody(), new $className()
                                    );

                                    // Override the parameters by the model instance
                                    $dispatcher->setParam($i, $requestObject);
                                }
                                catch(\Exception $ex)
                                {
                                    $dispatcher->forward([
                                        'controller' => 'index',
                                        'action' => 'badRequest'
                                    ]);
                                }
                            }

                            #endregion
                        }
                    }
                    catch (\Exception $e)
                    {
                        // An exception has occurred, maybe the class or action does not exist?
                    }
                }
            );

            $eventsManager->attach('dispatch', new AuthenticationAnnontation());

            $dispatcher = new Dispatcher();

            $dispatcher->setDefaultNamespace(
                'Reloaded\\UnrealEngine4\\Web\\Controllers'
            );

            $dispatcher->setEventsManager($eventsManager);

            return $dispatcher;
        });

        /**
         * JSONMapper is used to map a JSON structure to a concrete object type.
         *
         * @see https://github.com/cweiske/jsonmapper
         */
        $di->setShared('jsonMapper', function() {
            return new \JsonMapper();
        });

        /**
         * Mail Transport
         */
        $di->setShared('mailTransport', function() use($di) {
            $transport = new FileTransport();
            $options = new FileOptions([
                'path' => $di->getShared('config')->application->logsDir,
                'callback' => function() {
                    return sprintf(
                        'Message_%f_%s.mht',
                        microtime(true),
                        Rand::getString(8)
                    );
                }
            ]);

            $transport->setOptions($options);

            return $transport;
        });

        return $di;
    }
}