<?php

namespace Reloaded\UnrealEngine4\Models;

use Ramsey\Uuid\Uuid;

class PlayerAccountRecovery extends AbstractPlayerAccountRecovery
{
    public function beforeSave()
    {
        // Convert the GUID string into binary
        $this->PlayerId = hex2bin(str_replace('-', '', $this->PlayerId));
    }

    public function afterFetch()
    {
        // Convert the binary GUID to a GUID string
        $this->PlayerId = Uuid::fromBytes($this->PlayerId)->toString();
    }

    public function afterSave()
    {
        // Convert the binary GUID to a GUID string
        $this->PlayerId = Uuid::fromBytes($this->PlayerId)->toString();
    }
}
