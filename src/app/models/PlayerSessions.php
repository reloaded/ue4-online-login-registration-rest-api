<?php

namespace App\Models;

use IP;
use Ramsey\Uuid\Uuid;

class PlayerSessions extends AbstractPlayerSessions
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

    /**
     * Method to set the value of field SessionId
     *
     * @param string $SessionId
     * @return $this
     */
    public function setSessionId($SessionId)
    {
        $this->SessionId = hex2bin(str_replace('-', '', $SessionId));

        return $this;
    }

    /**
     * Returns the value of field SessionId
     *
     * @return string
     */
    public function getSessionId()
    {
        return Uuid::fromBytes($this->SessionId)->toString();
    }

    /**
     * Method to set the value of field RemoteIp
     *
     * @param string $RemoteIp
     * @return $this
     */
    public function setRemoteIp($RemoteIp)
    {
        $this->RemoteIp = IP::create($RemoteIp)->binary();

        return $this;
    }

    /**
     * Returns the value of field RemoteIp
     *
     * @return string
     */
    public function getRemoteIp()
    {
        return IP::create($this->RemoteIp)->humanReadable(true);
    }


//    public function beforeSave()
//    {
//        // Convert the GUID string into binary
//        $this->SessionId = hex2bin(str_replace('-', '', $this->SessionId));
//        $this->PlayerId = hex2bin(str_replace('-', '', $this->PlayerId));
//        $this->RemoteIp = IP::create($this->RemoteIp)->binary();
//    }

//    public function afterSave()
//    {
//        // Convert the binary GUID to a GUID string
//        $this->SessionId = Uuid::fromBytes($this->SessionId)->toString();
//        $this->PlayerId = Uuid::fromBytes($this->PlayerId)->toString();
//        $this->RemoteIp = IP::create($this->RemoteIp)->humanReadable(true);
//    }

//    public function afterFetch()
//    {
//        // Convert the binary GUID to a GUID string
//        $this->SessionId = Uuid::fromBytes($this->SessionId)->toString();
//        $this->PlayerId = Uuid::fromBytes($this->PlayerId)->toString();
//        $this->RemoteIp = IP::create($this->RemoteIp)->humanReadable(true);
//    }
}
