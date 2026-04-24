<?php

namespace App\Handlers\Commands;

use App\Attributes\AsMessengerCallback;
use App\Attributes\AsMessengerCommand;
use App\Builders\MessageBuilder;
use App\DTO\MessengerContextDto;
use App\Interfaces\CallbackHandlerInterface;
use App\Interfaces\CommandHandlerInterface;
use App\Repository\MessengerUserRepository;
use App\Telegram\DTO\CallbackQueryUpdateDto;
use App\Telegram\DTO\MessageUpdateDto;
use App\Telegram\Interfaces\UpdateInterface;

#[AsMessengerCommand('/^\/start/', 100)]
class StartHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly MessengerUserRepository $messengerUserRepository,
    ) {
    }

    public function __invoke(
        MessengerContextDto $context,
        MessageUpdateDto $update,
        array $matches
    ): void {
        $user = $this->messengerUserRepository->getOrCreate(
            $update->getChatId(),
            $context->messenger,
            $update->getUsername()
        );

        $build = MessageBuilder::for($update->getChatId())
            ->text("Выберите действие:")
            ->keyboardButton("Добавить заведение")
            ->keyboardButton("Удалить заведение")
            ->build();

        $context->service->sendMessage($build);
    }
}
