<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 3/1/2017 11:23 PM
 */

namespace App\Library\Responses;


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

    /**
     * FaultResponse constructor.
     * @param string $message
     * @param int $statusCode
     */
    public function __construct(string $message, int $statusCode)
    {
        $this->statusCode = $statusCode;
        $this->message = $message;
    }

    /**
     * Constructs a new FaultResponse from an exception. Uses the exception code as the response status code and
     * exception message for the fault message.
     *
     * @param \Exception $exception
     * @return FaultResponse
     */
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