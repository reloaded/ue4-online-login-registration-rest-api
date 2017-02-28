<?php

namespace Reloaded\UnrealEngine4\Web\Config;

use Phalcon\DiInterface;

class RouteConfig
{
    public static function RegisterRoutes(DiInterface $di)
    {
        /** @var \Phalcon\Mvc\Router $router */
        $router = $di->getShared("router");

        $router->notFound([
            'controller' => 'index',
            'action' => 'route404'
        ]);

        $router->handle();

    }
}