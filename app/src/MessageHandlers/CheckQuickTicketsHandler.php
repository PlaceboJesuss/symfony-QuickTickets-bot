<?php

namespace App\MessageHandlers;

use App\Messages\CheckQuickTicketsMessage;
use App\Services\Update\QuickTicketsUpdateService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CheckQuickTicketsHandler
{

    public function __construct(
        private readonly QuickTicketsUpdateService $service
    )
    {
    }

    public function __invoke(CheckQuickTicketsMessage $message): void
    {
        $this->service->update();
    }
}
