<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 3/3/2017 11:06 AM
 */

namespace App\Library\Responses;


class DataObjectResponse extends AbstractResponse implements IDataObjectResponse
{
    /**
     * The data object associated with the response.
     *
     * @var \stdClass
     */
    protected $data;

    #region Constructors

    /**
     * DataObjectResponse constructor.
     *
     * @param \stdClass $data
     * @param $statusCode
     */
    public function __construct(\stdClass $data = null, int $statusCode = null)
    {
        $this->statusCode = $statusCode;
        $this->data = $data;
    }

    #endregion

    /**
     * @inheritDoc
     */
    public function getData(): \stdClass
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function setData(\stdClass $data)
    {
        $this->data = $data;
    }

    /**
     * @inheritDoc
     */
    function jsonSerialize()
    {
        return parent::jsonSerialize() + [
                "Data" => $this->data
            ];
    }
}