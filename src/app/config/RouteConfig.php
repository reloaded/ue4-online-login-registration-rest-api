<?php

class RouteConfig
{
    public static function RegisterRoutes(\Phalcon\DiInterface $di)
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