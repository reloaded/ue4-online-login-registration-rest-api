<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 3/2/2017 2:09 AM
 */

namespace App\Library\Net\Responses;


class ValidationFieldError implements IValidationFieldError
{
    /** @var string */
    protected $message;

    /** @var string */
    protected $field;

    public function __construct(string $field, string $message)
    {
        $this->field = $field;
        $this->message = $message;
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @inheritDoc
     */
    public function getField(): string
    {
        return $this->field;
    }

}