<?php

namespace App\DTO;

use App\Entity\Messenger;
use App\Interfaces\MessengerClientInterface;
use App\Interfaces\MessengerServiceInterface;

class MessengerContextDto
{
    public function __construct(
        public readonly Messenger $messenger,
        public readonly MessengerServiceInterface $service,
    ) {}
}
