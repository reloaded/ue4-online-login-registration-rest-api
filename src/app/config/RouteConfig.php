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
            'action' => 'route404'
        ]);

        $router->addPost('/account/register', [
            'controller' => 'account',
            'action' => 'register'
        ]);

        $router->addPost('/account/login', [
            'controller' => 'account',
            'action' => 'login'
        ]);

        $router->addPatch('/account/activate', [
            'controller' => 'account',
            'action' => 'activate'
        ]);

        $router->addPost('/account/recover-password', [
            'controller' => 'account',
            'action' => 'recoverPassword'
        ]);

        $router->addDelete("/session/{sessionId:([{(]?[0-9A-Fa-f]{8}[-]?([0-9A-Fa-f]{4}[-]?){3}[0-9A-Fa-f]{12}[)}]?)}", [
            'controller' => 'session',
            'action' => 'logout'
        ]);

        $router->addPatch("/session/{sessionId:([{(]?[0-9A-Fa-f]{8}[-]?([0-9A-Fa-f]{4}[-]?){3}[0-9A-Fa-f]{12}[)}]?)}/heartbeat", [
            'controller' => 'session',
            'action' => 'heartbeat'
        ]);

        $router->handle();
    }
}