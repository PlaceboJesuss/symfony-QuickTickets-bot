<?php

namespace App\Schedulers;

use App\Messages\TelegramPollMessage;
use Symfony\Component\DependencyInjection\Attribute\When;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;

#[When(env: 'dev')]
#[AsSchedule('telegram_poll')]
class TelegramPollScheduler implements ScheduleProviderInterface
{
    public function getSchedule(): Schedule
    {
        return (new Schedule())
            ->add(RecurringMessage::every('5 seconds', new TelegramPollMessage()));
    }
}
