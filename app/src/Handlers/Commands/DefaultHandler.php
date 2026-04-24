<?php

namespace App\Handlers\Commands;

use App\Attributes\AsMessengerCommand;
use App\Builders\MessageBuilder;
use App\Clients\TelegramClient;
use App\DTO\MessengerContextDto;
use App\Interfaces\CommandHandlerInterface;
use App\Repository\MessengerUserRepository;
use App\Services\Import\QuickTicketsImportService;
use App\Telegram\DTO\MessageUpdateDto;

#[AsMessengerCommand('/.+/', -100)]
class DefaultHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly MessengerUserRepository $messengerUserRepository,
        private readonly QuickTicketsImportService $quickTicketsImportService,
        private readonly TelegramClient $telegramClient,
    ) {}

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

        $build = MessageBuilder::for($user->getChatId())
            ->text("Выберите действие")
            ->keyboardButton("Добавить заведение")
            ->keyboardButton("Удалить заведение")
            ->build();

        if (str_starts_with($update->getText(), "https://quicktickets.ru/")) {
            $placeUrl = $this->normalizeQuickticketsUrl($update->getText());
            $place = $this->quickTicketsImportService->import($placeUrl, $user);

            $successBuild = MessageBuilder::for($user->getChatId())
                ->text("Заведение \"" . $place->getName() . "\" добавлено.")
                ->build();

            $this->telegramClient->send($successBuild);
        }

        $this->telegramClient->send($build);
    }

    private function normalizeQuickticketsUrl(
        string $url
    ): ?string {
        $parts = parse_url($url);

        if (!isset($parts['scheme'], $parts['host'], $parts['path'])) {
            return null; // если ссылка битая - null
        }

        // Берём только первые 2 сегмента пути
        $pathParts = explode('/', trim($parts['path'], '/'));
        $normalizedPath = implode('/', array_slice($pathParts, 0, 1));

        return $parts['scheme'] . '://' . $parts['host'] . '/' . $normalizedPath;
    }
}
