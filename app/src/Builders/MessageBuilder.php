<?php

namespace App\Builders;

use App\DTO\MessagePayloadDto;

class MessageBuilder
{
    private int|string $chatId;

    private ?string $text = null;
    private ?string $photo = null;

    private array $inlineButtons = [];
    private array $keyboardButtons = [];

    public static function for(int|string $chatId): self
    {
        $self = new self();
        $self->chatId = $chatId;

        return $self;
    }

    public function text(string $text): self
    {
        $this->text = $text;
        return $this;
    }

    public function photo(string $url): self
    {
        $this->photo = $url;
        return $this;
    }

    public function urlButton(string $text, string $url): self
    {
        $this->inlineButtons[] = [
            ['text' => $text, 'url' => $url]
        ];

        return $this;
    }

    public function inlineButton(string $text, string $payload): self
    {
        $this->inlineButtons[] = [
            ['text' => $text, 'callback_data' => $payload]
        ];

        return $this;
    }

    public function keyboardButton(string $text): self
    {
        $this->keyboardButtons[] = [
            ['text' => $text]
        ];

        return $this;
    }

    public function build(): MessagePayloadDto
    {
        return new MessagePayloadDto(
            chatId: $this->chatId,
            text: $this->text,
            photo: $this->photo,
            inlineKeyboard: $this->inlineButtons,
            replyKeyboard: $this->keyboardButtons,
        );
    }
}
