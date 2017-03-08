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
        $validationMessages = $emailValidation->validate(null, $this);
        $this->appendValidationMessages($validationMessages);

        $inGameNameValidation = new InGameName();
        $validationMessages = $inGameNameValidation->validate(null, $this);
        $this->appendValidationMessages($validationMessages);

        $personalNameValidation = new PersonalName();
        $validationMessages = $personalNameValidation->validate(null, $this);
        $this->appendValidationMessages($validationMessages);

        return !$this->validationHasFailed();
    }
}
