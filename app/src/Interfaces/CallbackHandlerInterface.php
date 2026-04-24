<?php

namespace App\Interfaces;

use App\DTO\MessengerContextDto;
use App\Telegram\DTO\CallbackQueryUpdateDto;

interface CallbackHandlerInterface
{
    public function __invoke(
        MessengerContextDto $context,
        CallbackQueryUpdateDto $update,
        array $matches
    ): void;
}
