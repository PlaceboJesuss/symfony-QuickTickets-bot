<?php

namespace App\Interfaces;

use App\DTO\MessagePayloadDto;
use App\Entity\Messenger;
use App\Enums\MessengerTypeEnum;
use Symfony\Contracts\HttpClient\ResponseInterface;

interface MessengerServiceInterface
{
    public function sendMessage(MessagePayloadDto $payload): void;

    public function setupWebhook(): void;

    public function setMessenger(Messenger $messenger): static;

    public function getUpdates(int $offset): ResponseInterface;

    public function getType(): MessengerTypeEnum;
}
