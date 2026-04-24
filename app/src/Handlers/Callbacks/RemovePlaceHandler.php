<?php

namespace App\Handlers\Callbacks;

use App\Attributes\AsMessengerCallback;
use App\Builders\MessageBuilder;
use App\DTO\MessengerContextDto;
use App\Interfaces\CallbackHandlerInterface;
use App\Repository\MessengerUserRepository;
use App\Repository\PlaceRepository;
use App\Telegram\DTO\CallbackQueryUpdateDto;
use App\Telegram\Interfaces\UpdateInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

#[AsMessengerCallback('/^remove_place:(?P<place_id>\d+)$/')]
class RemovePlaceHandler implements CallbackHandlerInterface
{
    public function __construct(
        private readonly MessengerUserRepository $userRepository,
        private readonly PlaceRepository $placeRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(
        MessengerContextDto $context,
        CallbackQueryUpdateDto $update,
        array $matches
    ): void {
        $user = $this->userRepository->findOneByChatAndMessenger($update->getChatId(), $context->messenger);
        $place = $this->placeRepository->findOneById($matches['place_id']);
        $user->removePlace($place);

        $this->entityManager->flush();

        $build = MessageBuilder::for($user->getChatId())->text("Заведение удалено")->build();

        $context->service->sendMessage($build);
    }
}
