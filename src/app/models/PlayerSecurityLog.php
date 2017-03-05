<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;

class PlayerSecurityLog extends AbstractPlayerSecurityLog
{
    public function beforeSave()
    {
        // Convert the GUID string into binary
        $this->Id = hex2bin(str_replace('-', '', $this->Id));
        $this->PlayerId = hex2bin(str_replace('-', '', $this->PlayerId));
    }

    public function afterFetch()
    {
        // Convert the binary GUID to a GUID string
        $this->Id = Uuid::fromBytes($this->Id)->toString();
        $this->PlayerId = Uuid::fromBytes($this->PlayerId)->toString();
    }

    public function afterSave()
    {
        // Convert the binary GUID to a GUID string
        $this->Id = Uuid::fromBytes($this->Id)->toString();
        $this->PlayerId = Uuid::fromBytes($this->PlayerId)->toString();
    }
}
