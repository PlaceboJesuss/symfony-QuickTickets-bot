<?php

namespace App\DTO;

class MessagePayloadDto
{
    public function __construct(
        public readonly int|string $chatId,
        public readonly ?string $text = null,
        public readonly ?string $photo = null,
        public readonly array $inlineKeyboard = [],
        public readonly array $replyKeyboard = [],
    ) {}
}
