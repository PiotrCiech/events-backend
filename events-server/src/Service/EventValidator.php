<?php

namespace App\Service;

use App\Model\DeviceMalfunction;
use App\Model\Event;

class EventValidator
{
    public function validate(Event $event): void
    {
        if (empty($event->getDeviceId())) {
            throw new \InvalidArgumentException('Device ID is required');
        }

        if (empty($event->getEventDate())) {
            throw new \InvalidArgumentException('Event date is required');
        }

        if ($event instanceof DeviceMalfunction) {
            if (empty($event->getReasonCode()) || empty($event->getReasonText())) {
                throw new \InvalidArgumentException('Reason code and text are required for device malfunction');
            }
        }
    }
}