<?php

namespace App\Services;

use App\Entity\Performance;
use App\Entity\Session;
use App\Parsers\QuickTicketsParsers\SessionParser;
use Doctrine\ORM\EntityManagerInterface;
use simple_html_dom\simple_html_dom_node;

class SessionService
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
    }

    public function create(Session $schedule): Session
    {
        $this->em->persist($schedule);
        $schedule->getPerformance()->addSchedule($schedule);

        return $schedule;
    }
}
