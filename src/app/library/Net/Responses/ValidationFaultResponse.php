<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 3/2/2017 12:45 AM
 */

namespace App\Library\Net\Responses;

use Phalcon\Validation\Message\Group;


/**
 * Represents a faulted response that can have an associated HTTP status code and a list of field validation errors.
 *
 * Class ValidationFaultResponse
 * @package App\Library\Net
 */
class ValidationFaultResponse extends AbstractResponse implements IValidationFaultResponse, \JsonSerializable
{
    /** @var IValidationFieldError[] */
    protected $faultedFields;

    #region Constructors
    public function __construct(int $statusCode = null)
    {
        $this->statusCode = $statusCode;
    }
    #endregion

    /**
     * @inheritDoc
     */
    public function addValidationError(IValidationFieldError $faultedField)
    {
        $this->faultedFields[] = $faultedField;
    }

    /**
     * @inheritDoc
     */
    public function removeFieldValidationErrors(string $field)
    {
        $filteredList = array_filter(
            $this->faultedFields,
            function(IValidationFieldError $faultedField) use($field) {
                return $faultedField->getField() !== $field;
            }
        );

        $this->faultedFields = array_values($filteredList);
    }

    /**
     * @inheritDoc
     */
    public function getValidationErrors(): array
    {
        return $this->faultedFields;
    }

    /**
     * @inheritDoc
     */
    function jsonSerialize()
    {
        return parent::jsonSerialize() + [
            "FaultedFields" => array_map(function(IValidationFieldError $faultedField) {
                return [
                    "Field" => $faultedField->getField(),
                    "Message" => $faultedField->getMessage()
                ];
            }, $this->faultedFields)
        ];
    }

    /**
     * @inheritDoc
     */
    public function addValidationErrors(array $faultedFields)
    {
        foreach($faultedFields as $field)
        {
            if(!$field instanceof IValidationFieldError)
            {
                throw new \InvalidArgumentException('Expected instance of IValidationFieldError.');
            }

            $this->addValidationError($field);
        }
    }

    /**
     * Adds each message in a Phalcon validation message group to the fault response. Each Phalcon validation message
     * is converted to a IValidationFieldError.
     *
     * @param Group $group
     */
    public function addPhalconValidations(Group $group)
    {
        foreach($group as $field)
        {
            $this->addValidationError(new ValidationFieldError($field->getField(), $field->getMessage()));
        }
    }
}