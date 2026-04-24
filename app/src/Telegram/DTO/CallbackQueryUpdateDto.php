<?php

namespace App\Telegram\DTO;

use App\Telegram\Interfaces\UpdateInterface;

class CallbackQueryUpdateDto implements UpdateInterface
{
    public function __construct(
        private readonly int $chatId,
        private readonly string $data,
        private readonly string $callbackId,
    ) {
    }

    public function getChatId(): int
    {
        return $this->chatId;
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function getCallbackId(): string
    {
        return $this->callbackId;
    }
}
