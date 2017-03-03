<?php

namespace Reloaded\UnrealEngine4\Models;

use Ramsey\Uuid\Uuid;

class Players extends AbstractPlayers
{
    public function beforeSave()
    {
        // Convert the GUID string into binary
        $this->Id = hex2bin(str_replace('-', '', $this->Id));
    }

    public function afterFetch()
    {
        // Convert the binary GUID to a GUID string
        $this->Id = Uuid::fromBytes($this->Id)->toString();
    }

    public function afterSave()
    {
        // Convert the binary GUID to a GUID string
        $this->Id = Uuid::fromBytes($this->Id)->toString();
    }
}
