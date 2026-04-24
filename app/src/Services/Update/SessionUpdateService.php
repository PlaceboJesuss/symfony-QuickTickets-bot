<?php

namespace App\Services\Update;

use App\Builders\MessageBuilder;
use App\Entity\Performance;
use App\Entity\Place;
use App\Entity\Session;
use App\Parsers\QuickTicketsParsers\PerformanceParser;
use App\Parsers\QuickTicketsParsers\SessionParser;
use App\Repository\SessionRepository;
use App\Resolver\MessengerServiceResolver;
use App\Services\SessionService;
use Carbon\Carbon;
use Psr\Log\LoggerInterface;
use simple_html_dom\simple_html_dom_node;

class SessionUpdateService
{
    public function __construct(
        private SessionParser $parser,
        private PerformanceParser $performanceParser,
        private SessionService $service,
        private SessionRepository $sessionRepository,
        private MessengerServiceResolver $messengerServiceResolver,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @param simple_html_dom_node $dom
     * @param Performance $performance
     * @param Place $place
     * @return void
     */
    public function update(simple_html_dom_node $dom, Performance $performance, Place $place): void
    {
        $sessionNodes = $this->parser->getPerformanceSessions($dom);
        $image = $this->performanceParser->getPerformanceImage($dom);

        foreach ($sessionNodes as $sessionNode) {
            try {
                $soldOut = $this->parser->getSessionSoldOut($sessionNode);
                $timestamp = $this->parser->getSessionTimestamp($sessionNode);


                $session = $this->sessionRepository->getByPerformanceAndTimestamp($performance, $timestamp);

                if (!$session) {
                    $text = "Появился новый спектакль \"{$performance->getName()}\"\n";
                    $text .= Carbon::createFromTimestamp($timestamp, 'UTC')
                        ->setTimezone('Europe/Moscow')
                        ->format('d.m.Y H:i');
                    $text .= "\n\n" . $place->getName();

                    $href = $this->parser->getSessionLink($sessionNode);
                    $url = "https://quicktickets.ru$href";

                    foreach ($place->getMessengerUsers() as $user) {
                        $builder = MessageBuilder::for($user->getChatId())
                            ->text($text)
                            ->urlButton('Купить билет', $url);

                        if ($image) {
                            $builder->photo($image);
                        }

                        $service = $this->messengerServiceResolver->resolve($user->getMessenger());
                        $service->sendMessage($builder->build());
                    }

                    $this->service->create(
                        (new Session())
                            ->setPerformance($performance)
                            ->setIsSoldOut($soldOut)
                            ->setTimestamp($timestamp)
                    );
                } else {
                    $text = "Появились билеты на \"{$performance->getName()}\"\n";
                    $text .= Carbon::createFromTimestamp($timestamp, 'UTC')
                        ->setTimezone('Europe/Moscow')
                        ->format('d.m.Y H:i');
                    $text .= "\n\n" . $place->getName();

                    $href = $this->parser->getSessionLink($sessionNode);
                    $url = "https://quicktickets.ru$href";

                    if ($session->isSoldOut() !== $soldOut) {
                        if ($session->isSoldOut() === true) {
                            $this->logger->info("12345");

                            foreach ($place->getMessengerUsers() as $user) {
                                $builder = MessageBuilder::for($user->getChatId())
                                    ->text($text)
                                    ->urlButton('Купить билет', $url);

                                if ($image) {
                                    $builder->photo($image);
                                }

                                $service = $this->messengerServiceResolver->resolve($user->getMessenger());
                                $service->sendMessage($builder->build());
                            }
                        }

                        $session->setIsSoldOut($soldOut);
                    }
                }
            } catch (\Exception $e) {
                continue;
            }
        }
    }
}
