<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 3/2/2017 12:51 AM
 */

namespace App\Library\Responses;


/**
 * Represents a faulted response that can have a message.
 *
 * Interface IFaultResponse
 * @package App\Library\Net
 */
interface IFaultResponse
{
    /**
     * Sets the message associated with the fault.
     *
     * @param string $message
     */
    public function setMessage(string $message);

    /**
     * Gets the message associated with the fault.
     *
     * @return string
     */
    public function getMessage(): string;
}