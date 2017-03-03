<?php

namespace Reloaded\UnrealEngine4\Models;

use Ramsey\Uuid\Uuid;

class PlayerSessions extends AbstractPlayerSessions
{
    public function beforeSave()
    {
        // Convert the GUID string into binary
        $this->SessionId = hex2bin(str_replace('-', '', $this->SessionId));
        $this->PlayerId = hex2bin(str_replace('-', '', $this->PlayerId));
    }

    public function afterFetch()
    {
        // Convert the binary GUID to a GUID string
        $this->SessionId = Uuid::fromBytes($this->SessionId)->toString();
        $this->PlayerId = Uuid::fromBytes($this->PlayerId)->toString();
    }

    public function afterSave()
    {
        // Convert the binary GUID to a GUID string
        $this->SessionId = Uuid::fromBytes($this->SessionId)->toString();
        $this->PlayerId = Uuid::fromBytes($this->PlayerId)->toString();
    }
}
