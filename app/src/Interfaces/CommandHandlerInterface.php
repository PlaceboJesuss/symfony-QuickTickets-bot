<?php

namespace App\Interfaces;

use App\DTO\MessengerContextDto;
use App\Telegram\DTO\CallbackQueryUpdateDto;
use App\Telegram\DTO\MessageUpdateDto;

interface CommandHandlerInterface
{
    public function __invoke(
        MessengerContextDto $context,
        MessageUpdateDto $update,
        array $matches
    ): void;
}
