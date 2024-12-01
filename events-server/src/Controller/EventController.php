<?php

namespace App\Controller;

use App\Model\DeviceMalfunction;
use App\Model\Event;
use App\Model\TemperatureExceeded;
use App\Model\DoorUnlocked;
use App\Service\EventHandler;
use App\Service\EventValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    public function __construct(
        private readonly EventValidator $validator,
        private readonly EventHandler   $handler
    ) {}

    #[Route('/api/event',name:'api_event' ,methods: ['POST'])]
    public function handleEvent(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            $event = $this->createEventFromData($data);
            $this->validator->validate($event);
            $this->handler->handle($event);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }

        return new JsonResponse([
            'status' => 'success',
            'event' => [
                'deviceId' => $event->getDeviceId(),
                'eventDate' => $event->getEventDate()->format(\DateTime::ATOM),
                'type' => $event->getType(),
                'evtData' => $this->getEventData($event)
            ]
        ]);
    }

    private function getEventData(Event $event): array
    {
        if ($event instanceof DeviceMalfunction) {
            return [
                'reasonCode' => $event->getReasonCode(),
                'reasonText' => $event->getReasonText()
            ];
        } elseif ($event instanceof TemperatureExceeded) {
            return [
                'temp' => $event->getTemp(),
                'threshold' => $event->getThreshold()
            ];
        } elseif ($event instanceof DoorUnlocked) {
            return [
                'unlockDate' => $event->getUnlockDate()->format(\DateTime::ATOM)
            ];
        }

        return [];
    }
    private function createEventFromData(array $data): TemperatureExceeded|DoorUnlocked|DeviceMalfunction
    {
        return match ($data['type']) {
            'deviceMalfunction' => new DeviceMalfunction(
                $data['deviceId'],
                new \DateTime('@' . $data['eventDate']),
                $data['evtData']['reasonCode'],
                $data['evtData']['reasonText']
            ),
            'temperatureExceeded' => new TemperatureExceeded(
                $data['deviceId'],
                new \DateTime('@' . $data['eventDate']),
                $data['evtData']['temp'],
                $data['evtData']['threshold']
            ),
            'doorUnlocked' => new DoorUnlocked(
                $data['deviceId'],
                new \DateTime('@' . $data['eventDate']),
                new \DateTime('@' . $data['evtData']['unlockDate'])
            ),
            default => throw new \InvalidArgumentException('Unknown event type'),
        };
    }
}