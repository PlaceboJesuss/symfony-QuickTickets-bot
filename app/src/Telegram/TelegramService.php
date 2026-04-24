<?php

namespace App\Telegram;

use App\Attributes\AsMessengerService;
use App\Clients\TelegramClient;
use App\DTO\MessagePayloadDto;
use App\Entity\Messenger;
use App\Enums\MessengerTypeEnum;
use App\Interfaces\MessengerServiceInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class TelegramService implements MessengerServiceInterface
{
    private Messenger $messenger;

    public function __construct(
        private TelegramClient $client,
        private UrlGeneratorInterface $router
    ) {
    }

    public function getUpdates(int $offset): ResponseInterface
    {
        return $this->client->getUpdates($offset);
    }

    public function sendMessage(MessagePayloadDto $payload): void
    {
        $this->client->send($payload);
    }

    public function setupWebhook(): void
    {
        $this->client->setupWebhook($this->getWebhookUrl());
    }

    private function getWebhookUrl(): string
    {
        $url = $this->router->generate(
            'telegram_hook',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return preg_replace('/^http:/', 'https:', $url);
    }

    public function setMessenger(Messenger $messenger): static
    {
        $this->messenger = $messenger;
        $this->client->setToken($this->messenger->getToken());
        return $this;
    }

    public function getType(): MessengerTypeEnum
    {
        return MessengerTypeEnum::TELEGRAM;
    }
}
