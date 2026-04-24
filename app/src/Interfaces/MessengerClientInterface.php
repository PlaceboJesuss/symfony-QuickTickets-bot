<?php

namespace App\Interfaces;

use App\DTO\MessagePayloadDto;
use App\Enums\MessengerTypeEnum;

interface MessengerClientInterface
{
    public function send(MessagePayloadDto $payload): void;

    public function setToken(string $token): static;

    public function setupWebhook(string $url): void;
}
