<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 3/2/2017 12:47 AM
 */

namespace App\Library\Responses;


/**
 * Represents a response that can have an associated HTTP status code.
 *
 * Interface IResponse
 * @package App\Library\Net
 */
interface IResponse
{
    /**
     * Sets the HttpStatusCode associated with the fault.
     *
     * @var int
     * @see HttpStatusCode
     */
    public function setHttpStatusCode(int $statusCode);

    /**
     * Gets the HttpStatusCode associated with the fault.
     *
     * @var int
     * @see HttpStatusCode
     * @return int
     */
    public function getHttpStatusCode(): int;
}