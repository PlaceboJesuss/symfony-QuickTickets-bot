<?php

namespace App\Commands;

use App\Repository\MessengerRepository;
use App\Resolver\MessengerServiceResolver;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:set-webhook')]
class SetWebhookCommand extends Command
{
    public function __construct(
        private MessengerRepository $messengerRepository,
        private MessengerServiceResolver $resolver,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $messengers = $this->messengerRepository->findAll();

        foreach ($messengers as $messenger) {
            $service = $this->resolver->resolve($messenger);

            $service->setupWebhook();
        }

        $output->writeln('Webhook set');

        return Command::SUCCESS;
    }
}
