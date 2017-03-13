<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 2/28/2017 4:15 PM
 */

namespace Reloaded\UnrealEngine4\Web\Config;

use Phalcon\DiInterface;

class RouteConfig
{
    /**
     * Register each of our API routes with Phalcon.
     *
     * @param DiInterface $di Phalcon Dependency Injection service
     * @see https://docs.phalconphp.com/en/latest/reference/routing.html
     *
     */
    public static function registerRoutes(DiInterface $di)
    {
        /** @var \Phalcon\Mvc\Router $router */
        $router = $di->getShared("router");

        $router->clear();

        $router->removeExtraSlashes(true);

        $router->notFound([
            'controller' => 'index',
            'action' => 'routeNotFound'
        ]);

        $router->addPost('/account/register', [
            'controller' => 'account',
            'action' => 'register'
        ]);

        $router->addPatch('/account/activate', [
            'controller' => 'account',
            'action' => 'activate'
        ]);

        $router->addPost('/account/recover-password', [
            'controller' => 'account',
            'action' => 'recoverPassword'
        ]);

        $router->addPatch('/account/reset-password', [
            'controller' => 'account',
            'action' => 'resetPassword'
        ]);

        $router->addPost('/session', [
            'controller' => 'session',
            'action' => 'login'
        ]);

        $router->addDelete("/session", [
            'controller' => 'session',
            'action' => 'logout'
        ]);

        $router->addPatch("/session/heartbeat", [
            'controller' => 'session',
            'action' => 'heartbeat'
        ]);

        $router->handle();
    }
}