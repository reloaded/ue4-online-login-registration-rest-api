<?php

namespace App\Models;

use App\Validation\PlayerAccountRecovery\Code;
use Ramsey\Uuid\Uuid;

class PlayerAccountRecovery extends AbstractPlayerAccountRecovery
{
    /**
     * Method to set the value of field PlayerId
     *
     * @param string $PlayerId
     * @return $this
     */
    public function setPlayerId($PlayerId)
    {
        $this->PlayerId = hex2bin(str_replace('-', '', $PlayerId));

        return $this;
    }

    /**
     * Returns the value of field PlayerId
     *
     * @return string
     */
    public function getPlayerId()
    {
        return Uuid::fromBytes($this->PlayerId)->toString();
    }

//    public function beforeSave()
//    {
//        // Convert the GUID string into binary
//        $this->PlayerId = hex2bin(str_replace('-', '', $this->PlayerId));
//    }
//
//    public function afterFetch()
//    {
//        // Convert the binary GUID to a GUID string
//        $this->PlayerId = Uuid::fromBytes($this->PlayerId)->toString();
//    }
//
//    public function afterSave()
//    {
//        // Convert the binary GUID to a GUID string
//        $this->PlayerId = Uuid::fromBytes($this->PlayerId)->toString();
//    }

    public function validation()
    {
        /** @var \Phalcon\Validation\Message\Group $validationMessages */

        $codeValidation = new Code();
        $this->appendValidationMessages($codeValidation->validate(null, $this));

        return !$this->validationHasFailed();
    }
}
