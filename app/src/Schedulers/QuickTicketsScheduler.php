<?php

namespace App\Schedulers;

use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\RecurringMessage;
use App\Messages\CheckQuickTicketsMessage;
use Symfony\Component\Scheduler\ScheduleProviderInterface;

#[AsSchedule('quick_tickets')]
class QuickTicketsScheduler implements ScheduleProviderInterface
{
    public function getSchedule(): Schedule
    {
        return (new Schedule())->add(RecurringMessage::every('30 seconds', new CheckQuickTicketsMessage()));
    }
}
