<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 3/2/2017 12:58 AM
 */

namespace App\Library\Net\Responses;


/**
 * Represents a response object that can have a list of user input validation errors.
 *
 * Interface IValidationFaultResponse
 * @package App\Library\Net
 */
interface IValidationFaultResponse
{
    /**
     * Adds a field validation error to the response.
     *
     * @param IValidationFieldError $faultedField
     */
    public function addValidationError(IValidationFieldError $faultedField);

    /**
     * Removes all validation errors for the given field from the response.
     *
     * @param string $field
     */
    public function removeFieldValidationErrors(string $field);

    /**
     * Returns a list of field validation errors.
     *
     * @return IValidationFieldError[]
     */
    public function getValidationErrors(): array;
}