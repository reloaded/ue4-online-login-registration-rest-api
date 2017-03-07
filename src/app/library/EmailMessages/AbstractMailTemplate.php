<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 3/6/2017 8:30 PM
 */

namespace App\Library\EmailMessages;

use Phalcon\Events\ManagerInterface;
use Phalcon\Mvc\View\Simple as SimpleView;


/**
 * An abstract class that provides an instance of \Phalcon\View for rendering email messages from view templates. Child
 * classes must implement a render() method that will setup the template variables in the View engine, render
 * the template and return the rendered template.
 *
 * @package App\Library\EmailMessages
 */
abstract class AbstractMailTemplate implements IMessageTemplate
{
    /** @var SimpleView */
    protected $_view;

    #region Constructors

    public function __construct()
    {
        $this->_view = new SimpleView();
    }

    #endregion

    /**
     * @inheritDoc
     */
    public function setEventsManager(ManagerInterface $eventsManager)
    {
        $this->_view->setEventsManager($eventsManager);
    }

    /**
     * @inheritDoc
     */
    public function getEventsManager(): ManagerInterface
    {
        return $this->_view->getEventsManager();
    }


}