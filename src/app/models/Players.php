<?php

namespace App\Models;

use App\Models\Validation\Players as PlayersValidation;
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
        $validation = new PlayersValidation();

        return $this->validate($validation);
    }
}
