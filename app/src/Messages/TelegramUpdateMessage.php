<?php

namespace App\Messages;

class TelegramUpdateMessage
{
    public function __construct(
        private array $payload
    ) {}

    public function getPayload(): array
    {
        return $this->payload;
    }
}
