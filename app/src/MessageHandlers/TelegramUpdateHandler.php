<?php

namespace App\MessageHandlers;

use App\Enums\MessengerTypeEnum;
use App\Messages\TelegramUpdateMessage;
use App\Repository\MessengerRepository;
use App\Resolver\CallbackResolver;
use App\Resolver\CommandResolver;
use App\Resolver\MessengerContextResolver;
use App\Telegram\DTO\CallbackQueryUpdateDto;
use App\Telegram\DTO\MessageUpdateDto;
use App\Telegram\Factory\UpdateFactory;

class TelegramUpdateHandler
{
    public function __construct(
        private readonly UpdateFactory $updateFactory,
        private readonly CommandResolver $commandResolver,
        private readonly CallbackResolver $callbackResolver,
        private readonly MessengerContextResolver $messengerContextResolver,
        private readonly MessengerRepository $messengerRepository,
    )
    {
    }

    public function __invoke(TelegramUpdateMessage $message)
    {
        $messengers = $this->messengerRepository->findAllByType(MessengerTypeEnum::TELEGRAM);

        foreach ($messengers as $messenger) {
            $messengerContext = $this->messengerContextResolver->resolve($messenger);

            $update = $this->updateFactory->fromArray($message->getPayload());

            if ($update instanceof CallbackQueryUpdateDto) {
                $this->callbackResolver->resolve($messengerContext, $update);
            } elseif ($update instanceof MessageUpdateDto) {
                $this->commandResolver->resolve($messengerContext, $update);
            }
        }
    }
}
