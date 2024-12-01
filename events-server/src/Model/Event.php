<?php

namespace App\Model;

abstract class Event
{

    public function __construct(
        protected string $deviceId,
        protected \DateTime $eventDate
    ) {}

    abstract public function getType(): string;

    public function getDeviceId(): string
    {
        return $this->deviceId;
    }

    public function getEventDate(): \DateTime
    {
        return $this->eventDate;
    }
}