<?php

namespace App\Controller;

use App\Enums\MessengerTypeEnum;
use App\Messages\TelegramUpdateMessage;
use App\Repository\MessengerRepository;
use App\Resolver\CallbackResolver;
use App\Resolver\CommandResolver;
use App\Resolver\MessengerContextResolver;
use App\Telegram\DTO\CallbackQueryUpdateDto;
use App\Telegram\DTO\MessageUpdateDto;
use App\Telegram\Factory\UpdateFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

class TelegramController extends AbstractController
{
    #[Route('/telegram/hook', name: 'telegram_hook')]
    public function hook(Request $request, MessageBusInterface $bus): Response
    {
        $data = $request->toArray();
        $bus->dispatch(new TelegramUpdateMessage($data));

        return new Response('OK');
    }
}
