<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 3/2/2017 12:45 AM
 */

namespace App\Library\Net\Responses;


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

    /**
     * @inheritDoc
     */
    public function addValidationError(IValidationFieldError $faultedField): void
    {
        $this->faultedFields[] = $faultedField;
    }

    /**
     * @inheritDoc
     */
    public function removeFieldValidationErrors(string $field): void
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


}