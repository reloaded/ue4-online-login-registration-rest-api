<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 3/3/2017 6:02 PM
 */

namespace App\Library\Responses;


interface IDataObjectResponse
{
    /**
     * @return \stdClass
     */
    public function getData(): \stdClass;

    /**
     * @param \stdClass $data
     */
    public function setData(\stdClass $data);
}