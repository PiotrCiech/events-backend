<?php

namespace App\Model;

class DoorUnlocked extends Event
{
    private \DateTime $unlockDate;

    public function __construct(string $deviceId, \DateTime $eventDate, \DateTime $unlockDate)
    {
        parent::__construct($deviceId, $eventDate);
        $this->unlockDate = $unlockDate;
    }

    public function getType(): string
    {
        return 'doorUnlocked';
    }

    public function getUnlockDate(): \DateTime
    {
        return $this->unlockDate;
    }
}