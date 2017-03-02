<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 3/1/2017 11:23 PM
 */

namespace App\Library\Net\Responses;


/**
 * Represents a faulted response that can have an associated HTTP status code and error message.
 *
 * Class FaultResponse
 * @package App\Library\Net
 */
class FaultResponse extends AbstractResponse implements IFaultResponse, \JsonSerializable
{
    /**
     * The message associated with the fault.
     *
     * @var string
     */
    protected $message;

    #region Constructors
    public function __construct($message, $statusCode)
    {
        $this->statusCode = $statusCode;
        $this->message = $message;
    }

    public static function fromException(\Exception $exception)
    {
        return new self($exception->getMessage(), $exception->getCode());
    }
    #endregion

    /**
     * @inheritdoc
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
    }

    /**
     * @inheritdoc
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @inheritDoc
     */
    function jsonSerialize()
    {
        return parent::jsonSerialize() + [
            "Message" => $this->message
        ];
    }
}