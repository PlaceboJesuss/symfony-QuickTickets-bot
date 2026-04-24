<?php

namespace App\Resolver;

use App\DTO\MessengerContextDto;
use App\Entity\Messenger;
use App\Enums\MessengerTypeEnum;
use App\Interfaces\MessengerClientInterface;
use App\Interfaces\MessengerServiceInterface;
use App\Repository\MessengerRepository;
use Psr\Container\ContainerInterface;

class MessengerContextResolver
{
    public function __construct(
        private MessengerServiceResolver $resolver,
    ) {
    }

    public function resolve(Messenger $messenger): MessengerContextDto
    {
        $service = $this->resolver->resolve($messenger);

        return new MessengerContextDto(
            messenger: $messenger,
            service: $service,
        );
    }
}
