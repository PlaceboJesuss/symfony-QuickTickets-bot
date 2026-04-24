<?php

namespace App\Handlers\Commands;

use App\Attributes\AsMessengerCommand;
use App\Builders\MessageBuilder;
use App\DTO\MessengerContextDto;
use App\Interfaces\CommandHandlerInterface;
use App\Repository\MessengerUserRepository;
use App\Telegram\DTO\MessageUpdateDto;

#[AsMessengerCommand('/Удалить заведение/')]
class ShowSelectRemovePlaceHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly MessengerUserRepository $userRepository
    ) {
    }

    public function __invoke(
        MessengerContextDto $context,
        MessageUpdateDto $update,
        array $matches
    ): void {
        $user = $this->userRepository->findOneByChatAndMessenger($update->getChatId(), $context->messenger);

        $places = $user->getPlaces();

        if (!$places->isEmpty()) {
            $builder = MessageBuilder::for($update->getChatId())->text(
                "Выберите заведение, которое необходимо удалить"
            );

            foreach ($user->getPlaces() as $place) {
                $builder->inlineButton($place->getName(), "remove_place:" . $place->getId());
            }
        } else {
            $builder = MessageBuilder::for($update->getChatId())->text("У вас нет заведений");
        }

        $build = $builder->build();
        $context->service->sendMessage($build);
    }
}
