<?php

namespace App\Models;

use App\Validation\Player\Email;
use App\Validation\Player\InGameName;
use App\Validation\Player\PersonalName;
use Ramsey\Uuid\Uuid;

class Players extends AbstractPlayers
{
    /**
     * Method to set the value of field Id
     *
     * @param string $Id
     * @return $this
     */
    public function setId($Id)
    {
        $this->Id = hex2bin(str_replace('-', '', $Id));

        return $this;
    }

    /**
     * Returns the value of field Id
     *
     * @return string
     */
    public function getId()
    {
        return Uuid::fromBytes($this->Id)->toString();
    }

    /**
     * Method to set the value of field IsActivated
     *
     * @param bool $IsActivated
     * @return $this
     */
    public function setIsActivated($IsActivated)
    {
        $this->IsActivated = $IsActivated ? 1 : 0;

        return $this;
    }

    /**
     * Returns the value of field IsActivated
     *
     * @return bool
     */
    public function getIsActivated()
    {
        return (int) $this->IsActivated === 1;
    }




//    public function beforeSave()
//    {
//        // Convert the GUID string into binary
//        $this->Id = hex2bin(str_replace('-', '', $this->Id));
//    }
//
//    public function afterFetch()
//    {
//        // Convert the binary GUID to a GUID string
//        $this->Id = Uuid::fromBytes($this->Id)->toString();
//    }
//
//    public function afterSave()
//    {
//        // Convert the binary GUID to a GUID string
//        $this->Id = Uuid::fromBytes($this->Id)->toString();
//    }

    public function validation()
    {
        /** @var \Phalcon\Validation\Message\Group $validationMessages */

        $emailValidation = new Email();
        $this->appendValidationMessages($emailValidation->validate(null, $this));

        $inGameNameValidation = new InGameName();
        $this->appendValidationMessages($inGameNameValidation->validate(null, $this));

        $personalNameValidation = new PersonalName();
        $this->appendValidationMessages($personalNameValidation->validate(null, $this));

        return !$this->validationHasFailed();
    }
}
