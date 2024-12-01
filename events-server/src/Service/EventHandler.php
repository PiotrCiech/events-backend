<?php

namespace App\Service;

use App\Model\Event;

class EventHandler
{
    public function handle(Event $event): void
    {
        echo sprintf("Logging event: %s\n", $event->getType());

        switch ($event->getType()) {
            case 'deviceMalfunction':
                echo "Sending email for device malfunction.\n";
                break;
            case 'temperatureExceeded':
                echo "Making REST request for temperature exceeded.\n";
                break;
            case 'doorUnlocked':
                echo "Sending SMS for door unlocked.\n";
                break;
        }
    }
}