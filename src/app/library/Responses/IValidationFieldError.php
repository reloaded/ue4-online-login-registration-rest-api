<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 3/2/2017 1:13 AM
 */

namespace App\Library\Responses;


/**
 * Represents a single validation error for a field that can have an error message and a field identifier string.
 *
 * Interface IValidationFieldError
 * @package App\Library\Net
 */
interface IValidationFieldError
{
    /**
     * Returns the validation error message.
     *
     * @return string
     */
    public function getMessage(): string;

    /**
     * Returns a string identifying the field
     * .
     * @return string
     */
    public function getField(): string;
}