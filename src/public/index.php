<?php

use Reloaded\UnrealEngine4\Web\Config\DiConfig;
use Reloaded\UnrealEngine4\Web\Config\LoaderConfig;
use Reloaded\UnrealEngine4\Web\Config\RouteConfig;

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

require_once APP_PATH . '/config/DiConfig.php';
require_once APP_PATH . '/config/LoaderConfig.php';
require_once APP_PATH . '/config/RouteConfig.php';

error_reporting(E_ALL);

try {
    /**
     * Setup dependency injection
     */
    $di = DiConfig::registerDependencies();

    /**
     * Setup route handlers
     */
    RouteConfig::registerRoutes($di);

    /**
     * Setup autoloader
     */
    LoaderConfig::registerLoader($di);

    /**
     * Handle the request
     */
    $application = new \Phalcon\Mvc\Application($di);

    echo $application->handle()->getContent();

} catch (\Exception $e) {
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
