<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 3/6/2017 8:33 PM
 */

namespace App\Library\EmailMessages;

use Phalcon\Events\ManagerInterface;


/**
 * Represents an email template that returns an email message body ready for use in an email.
 *
 * @package App\Library\EmailMessages
 */
interface IMessageTemplate
{
    /**
     * Renders a HTML email message and returns the message string.
     *
     * @return string
     */
    public function renderHtml(): string;

    /**
     * Sets an events manager that will be attached to the view engine.
     *
     * @param ManagerInterface $eventsManager
     * @return mixed
     */
    public function setEventsManager(ManagerInterface $eventsManager);

    /**
     * Returns the events manager instance that was attached to the view engine.
     *
     * @return ManagerInterface
     */
    public function getEventsManager(): ManagerInterface;
}