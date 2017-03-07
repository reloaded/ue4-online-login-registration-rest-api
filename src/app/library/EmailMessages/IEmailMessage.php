<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 3/6/2017 10:18 PM
 */

namespace App\Library\EmailMessages;


interface IEmailMessage
{
    public function __construct(IMessageTemplate $messageTemplate);

    /**
     * Returns an email message
     * @return string
     */
    public function getMessageHtml(): string;

    public function getMessageText(): string;
}