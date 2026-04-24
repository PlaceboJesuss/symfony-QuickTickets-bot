<?php

namespace App\Telegram\DTO;

use App\Telegram\Interfaces\UpdateInterface;

class MessageUpdateDto implements UpdateInterface
{
    public function __construct(
        private readonly int $chatId,
        private readonly string $text,
        private readonly ?string $username,
    ) {}

    public function getChatId(): int
    {
        return $this->chatId;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }
}
