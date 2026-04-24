<?php

namespace App\Resolver;

use App\Interfaces\MessengerServiceInterface;
use App\Entity\Messenger;

class MessengerServiceResolver
{
    private array $services;

    public function __construct(
        iterable $services
    ) {
        /** @var MessengerServiceInterface $service */
        foreach ($services as $service) {
            $this->services[$service->getType()->value] = $service;
        }
    }

    public function resolve(Messenger $messenger): MessengerServiceInterface
    {
        $type = $messenger->getType();

        if (!isset($this->services[$type])) {
            throw new \RuntimeException("Service not found: $type");
        }

        $service = $this->services[$type];
        return $service->setMessenger($messenger);
    }
}
