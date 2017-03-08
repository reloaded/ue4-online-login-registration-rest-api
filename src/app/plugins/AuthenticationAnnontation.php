<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 3/8/2017 2:24 AM
 */

namespace App\Plugins;


use App\Models\PlayerSessions;
use Phalcon\Annotations\Annotation;
use Phalcon\Events\Event;
use Phalcon\Http\Request;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\User\Plugin;
use Ramsey\Uuid\Uuid;

/**
 * Adds support to restrict access to controllers and controller action methods by using PHP docblock annotations.
 *
 * Action method annotations take priority over class annotations.
 *
 * <code>
 * /** @authentication(allowAnonymous=false)
 * class AccountController extends \Phalcon\Controller {
 *
 *      @authentication(allowAnonymous=true, allowAuthenticated=false)
 *      public function registerAction(){
 *          // allows anonymous requests and denies authenticated requests
 *      }
 *
 *      @authentication(allowAnonymous=false, allowAuthenticated=true)
 *      public function updateAccountAction() {
 *          // denies anonymous requests and allows authenticated requests
 *      }
 *
 *      @authentication(allowAuthenticated=true)
 *      public function deleteAccountAction() {
 *          // denies anonymous requests and allows authenticated requests
 *      }
 *
 *      @authentication(allowAnonymous=true)
 *      public function recoverPasswordAction() {
 *          // allows anonymous requests and denies authenticated requests
 *      }
 *
 *      public function activateAction() {
 *          // denies anonymous requests and authenticated requests, inherited from the class annotation
 *      }
 *
 * }
 *
 * class SessionController extends \Phalcon\Controller {
 *
 *      public function loginAction(){
 *          // no authentication restriction on method or class so no restrictions what-so-ever
 *      }
 *
 * }
 * </code>
 *
 * @package App\Plugins
 */
class AuthenticationAnnontation extends Plugin
{
    /** @var \Phalcon\DiInterface */
    protected $_di;

    /** @var bool */
    protected $_isAuthenticated;

    /**
     * This action is executed before execute any action in the application
     *
     * @param Event $event [optional]
     * @param Dispatcher $dispatcher
     * @return bool
     */
    public function beforeDispatch(Event $event, Dispatcher $dispatcher)
    {
        $this->_di = $dispatcher->getDI();

        /** @var Request $request */
        $request = $this->_di->getShared('request');

        try
        {
            $sessionId = Uuid::fromString($request->getHeader('Sessionid'))->getBytes();

            $session = PlayerSessions::findFirst([
                'conditions' => 'SessionId = ?1 AND Expiration >= ?2',
                'bind' => [
                    1 => $sessionId,
                    2 => (new \DateTime())->format('Y-m-d H:i:s')
                ]
            ]);

            $this->_isAuthenticated = $session ? true : false;
        }
        catch(\Exception $ex)
        {
            $sessionId = null;
            $this->_isAuthenticated = false;
        }

        // Parse the annotations in the method
        $annotations = $this->annotations->getMethod(
            $dispatcher->getControllerClass(),
            $dispatcher->getActiveMethod()
        );

        // Check if the method has an annotation
        if ($annotations->has('authentication')) {
            if(!$this->_checkAnnotation($annotations->get('authentication')))
            {
                $dispatcher->forward(
                    [
                        "controller" => "index",
                        "action"     => "unauthorized",
                    ]
                );

                return false;
            }
        }

        // Parse the annotations in the class
        $annotations = $this->annotations->get($dispatcher->getControllerClass())->getClassAnnotations();

        // Check if the class has an annotation
        if ($annotations && $annotations->has('authentication')) {
            if(!$this->_checkAnnotation($annotations->get('authentication')))
            {
                $dispatcher->forward(
                    [
                        "controller" => "index",
                        "action"     => "unauthorized",
                    ]
                );

                return false;
            }
        }

        return true;
    }

    /**
     * @param Annotation $annotation
     * @return bool
     */
    protected function _checkAnnotation(Annotation $annotation)
    {
        $allowAnonymous = (bool)$annotation->getNamedParameter('allowAnonymous');
        $allowAuthenticated = (bool)$annotation->getNamedParameter('allowAuthenticated');

        if (!$allowAnonymous && !$this->_isAuthenticated) {
            return false;
        }

        if (!$allowAuthenticated && $this->_isAuthenticated) {
            return false;
        }

        return true;
    }
}