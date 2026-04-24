<?php

namespace App\Handlers\Commands;

use App\Attributes\AsMessengerCallback;
use App\Attributes\AsMessengerCommand;
use App\Builders\MessageBuilder;
use App\DTO\MessengerContextDto;
use App\Interfaces\CallbackHandlerInterface;
use App\Interfaces\CommandHandlerInterface;
use App\Telegram\DTO\CallbackQueryUpdateDto;
use App\Telegram\DTO\MessageUpdateDto;

#[AsMessengerCommand('/Добавить заведение/')]
class AddPlaceHandler implements CommandHandlerInterface
{
    public function __construct(
    ) {}

    public function __invoke(
        MessengerContextDto $context,
        MessageUpdateDto $update,
        array $matches
    ): void {
        $build = MessageBuilder::for($update->getChatId())->text("Введите ссылку на заведение")->build();

        $context->service->sendMessage($build);
    }
}
