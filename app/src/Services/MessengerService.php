<?php

namespace App\Services;

use App\DTO\CreateMessengerDto;
use App\Entity\Messenger;
use App\Entity\Performance;
use App\Entity\Session;
use App\Environment\AppEnv;
use App\Parsers\QuickTicketsParsers\SessionParser;
use App\Repository\MessengerRepository;
use App\Resolver\MessengerContextResolver;
use App\Resolver\MessengerServiceResolver;
use Doctrine\ORM\EntityManagerInterface;
use simple_html_dom\simple_html_dom_node;

class MessengerService
{
    public function __construct(
        private readonly MessengerRepository $messengerRepository,
        private readonly MessengerContextResolver $resolver,
        private readonly EntityManagerInterface $em,
        private readonly AppEnv $appEnv,
    ) {
    }

    public function create(CreateMessengerDto $dto): Messenger
    {
        $messenger = $this->messengerRepository->findOneByToken($dto->getToken());

        if ($messenger) {
            throw  new \DomainException('Messenger already exists.');
        }

        $messenger = (new Messenger())
            ->setName($dto->getName())
            ->setType($dto->getType())
            ->setToken($dto->getToken());

        $context = $this->resolver->resolve($messenger);

        if ($this->appEnv->isProd()) {
            $context->service->setupWebhook();
        }

        $this->em->persist($messenger);
        $this->em->flush();

        return $messenger;
    }
}
