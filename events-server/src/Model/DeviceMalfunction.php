<?php

namespace App\Model;

class DeviceMalfunction extends Event
{
    private int $reasonCode;
    private string $reasonText;

    public function __construct(string $deviceId, \DateTime $eventDate, int $reasonCode, string $reasonText)
    {
        parent::__construct($deviceId, $eventDate);
        $this->reasonCode = $reasonCode;
        $this->reasonText = $reasonText;
    }

    public function getType(): string
    {
        return 'deviceMalfunction';
    }

    public function getReasonCode(): int
    {
        return $this->reasonCode;
    }

    public function getReasonText(): string
    {
        return $this->reasonText;
    }
}