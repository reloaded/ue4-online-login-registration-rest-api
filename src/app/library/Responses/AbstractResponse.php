<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 3/1/2017 11:20 PM
 */

namespace App\Library\Responses;


use App\Library\Net\HttpStatusCode;

/**
 * Represents a response that has an associated HTTP status code.
 *
 * Class AbstractResponse
 * @package App\Library\Net
 */
abstract class AbstractResponse implements IResponse, \JsonSerializable
{
    /**
     * The HttpStatusCode associated with the fault.
     *
     * @var int
     * @see HttpStatusCode
     */
    protected $statusCode;

    /**
     * @inheritdoc
     */
    public function setHttpStatusCode(int $statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @inheritdoc
     */
    public function getHttpStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        return [
            "StatusCode" => $this->statusCode
        ];
    }


}