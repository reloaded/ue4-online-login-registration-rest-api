<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 3/1/2017 11:23 PM
 */

namespace Reloaded\UnrealEngine4\Library\Responses;


use Reloaded\UnrealEngine4\Library\Net\HttpStatusCode;

class FaultResponse extends AbstractResponse
{
    /** @var bool */
    public $Success = false;

    /**
     * The HttpStatusCode associated with the fault.
     *
     * @var int
     * @see HttpStatusCode
     */
    public $StatusCode;

    /**
     * The message associated with the fault.
     *
     * @var string
     */
    public $Message;

    #region Constructors
    public function __construct($message, $statusCode)
    {
        $this->StatusCode = $statusCode;
        $this->Message = $message;
    }

    public static function fromException(\Exception $exception)
    {
        return new self($exception->getMessage(), $exception->getCode());
    }
    #endregion
}