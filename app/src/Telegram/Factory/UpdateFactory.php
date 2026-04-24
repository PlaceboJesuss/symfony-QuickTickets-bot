<?php

namespace App\Telegram\Factory;

use App\Telegram\DTO\CallbackQueryUpdateDto;
use App\Telegram\DTO\MessageUpdateDto;
use App\Telegram\Interfaces\UpdateInterface;
use Symfony\Component\HttpFoundation\Request;

class UpdateFactory
{

    public function fromArray(array $data): UpdateInterface
    {
        return match (true) {
            isset($data['callback_query']) => new CallbackQueryUpdateDto(
                chatId: $data['callback_query']['message']['chat']['id'],
                data: $data['callback_query']['data'],
                callbackId: $data['callback_query']['id'],
            ),
            isset($data['message']) => new MessageUpdateDto(
                chatId: $data['message']['chat']['id'],
                text: $data['message']['text'] ?? null,
                username: $data['message']['from']['username'] ?? null,
            ),
            default => throw new \Exception('Unsupported'),
        };
    }
}
