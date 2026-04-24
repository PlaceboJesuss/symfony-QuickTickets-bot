<?php

namespace App\Clients;

use App\DTO\MessagePayloadDto;
use App\Interfaces\MessengerClientInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class TelegramClient implements MessengerClientInterface
{

    private string $token;

    public function __construct(
        private HttpClientInterface $http,
    ) {
    }

    public function send(MessagePayloadDto $payload): void
    {
        if ($payload->photo !== null) {
            $this->sendPhoto($payload);
            return;
        }

        $this->sendMessage($payload);
    }

    private function sendMessage(MessagePayloadDto $payload): void
    {
        $data = [
            'chat_id' => $payload->chatId,
            'text' => $payload->text ?? '',
        ];

        $replyMarkup = $this->buildReplyMarkup($payload);

        if ($replyMarkup !== null) {
            $data['reply_markup'] = $replyMarkup;
        }

        $this->request('sendMessage', $data);
    }

    private function sendPhoto(MessagePayloadDto $payload): void
    {
        $data = [
            'chat_id' => $payload->chatId,
            'photo' => $payload->photo,
        ];

        if ($payload->text) {
            $data['caption'] = $payload->text;
        }

        $replyMarkup = $this->buildReplyMarkup($payload);

        if ($replyMarkup !== null) {
            $data['reply_markup'] = $replyMarkup;
        }

        $this->request('sendPhoto', $data);
    }

    private function buildReplyMarkup(MessagePayloadDto $payload): ?array
    {
        if (!empty($payload->inlineKeyboard)) {
            return [
                'inline_keyboard' => $payload->inlineKeyboard
            ];
        }

        if (!empty($payload->replyKeyboard)) {
            return [
                'keyboard' => $payload->replyKeyboard,
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ];
        }

        return null;
    }

    public function setupWebhook(string $url): void
    {
        $this->http->request('POST', $this->url('setWebhook'), [
            'json' => [
                'url' => $url
            ]
        ]);
    }

    public function getUpdates($offset = 0): ResponseInterface
    {
        return $this->http->request(
            'GET',
            $this->url("getUpdates"),
            [
                'query' => [
                    'timeout' => 10,
                    'offset' => $offset,
                    'allowed_updates' => json_encode(["message", "callback_query"])
                ]
            ]
        );
    }

    private function request(string $method, array $data): void
    {
        $this->http->request(
            'POST',
            $this->url($method),
            [
                'json' => $data
            ]
        );
    }

    private function url(string $method): string
    {
        return "https://api.telegram.org/bot{$this->token}/{$method}";
    }

    public function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }
}
