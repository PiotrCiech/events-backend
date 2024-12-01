<?php

namespace App\Model;

class TemperatureExceeded extends Event
{
    private float $temp;
    private float $threshold;

    public function __construct(string $deviceId, \DateTime $eventDate, float $temp, float $threshold)
    {
        parent::__construct($deviceId, $eventDate);
        $this->temp = $temp;
        $this->threshold = $threshold;
    }

    public function getType(): string
    {
        return 'temperatureExceeded';
    }

    public function getTemp(): float
    {
        return $this->temp;
    }

    public function getThreshold(): float
    {
        return $this->threshold;
    }
}