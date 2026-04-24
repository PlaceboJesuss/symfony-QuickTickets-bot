<?php

namespace App\MessageHandlers;

use App\Enums\MessengerTypeEnum;
use App\Messages\TelegramPollMessage;
use App\Repository\MessengerRepository;
use App\Resolver\CallbackResolver;
use App\Resolver\CommandResolver;
use App\Resolver\MessengerContextResolver;
use App\Telegram\DTO\CallbackQueryUpdateDto;
use App\Telegram\DTO\MessageUpdateDto;
use App\Telegram\Factory\UpdateFactory;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsMessageHandler]
class TelegramPollHandler
{
    private int $offset = 0;

    public function __construct(
        private readonly UpdateFactory $updateFactory,
        private readonly CommandResolver $commandResolver,
        private readonly CallbackResolver $callbackResolver,
        private readonly MessengerContextResolver $messengerContextResolver,
        private MessengerRepository $messengerRepository,
        private  LoggerInterface $logger,

    ) {
    }

    public function __invoke(TelegramPollMessage $message): void
    {
        $messengers = $this->messengerRepository->findAll();

        foreach ($messengers as $messenger) {
            $messengerContext = $this->messengerContextResolver->resolve($messenger);

            $response = $messengerContext->service->getUpdates($this->offset);
            $data = $response->toArray();

            $this->logger->info("Update " . $response->getContent());


            foreach ($data['result'] as $update) {
                $this->offset = $update['update_id'] + 1;

                $this->logger->info("Update " . json_encode($update));


                $update = $this->updateFactory->fromArray($update);


                if ($update instanceof CallbackQueryUpdateDto) {
                    $this->callbackResolver->resolve($messengerContext, $update);
                } elseif ($update instanceof MessageUpdateDto) {
                    $this->commandResolver->resolve($messengerContext, $update);
                }
            }
        }
    }
}
