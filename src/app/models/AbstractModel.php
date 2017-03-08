<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 3/3/2017 7:31 PM
 */

namespace App\Models;


use Phalcon\Mvc\Model;
use Phalcon\Validation\Message\Group;
use Phalcon\Validation\MessageInterface;

abstract class AbstractModel extends Model
{
    /**
     * Converts a \Phalcon\Validation\MessageInterface to a model validation message and appends it to the end
     * of the model validation messages.
     *
     * @param MessageInterface $message
     * @return void
     */
    protected function appendValidationMessage(MessageInterface $message)
    {
        $this->appendMessage(new Model\Message(
            $message->getMessage(),
            $message->getField(),
            $message->getType(),
            $this
        ));
    }

    /**
     * Converts a list of \Phalcon\Validation\MessageInterface to model validation messages and appends them
     * to the end of the model validation messages.
     *
     * @param Group $messages
     * @return void
     */
    protected function appendValidationMessages(Group $messages)
    {
        foreach($messages as $message)
        {
            $this->appendValidationMessage($message);
        }
    }
}