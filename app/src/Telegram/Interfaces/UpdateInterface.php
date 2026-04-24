<?php

namespace App\Telegram\Interfaces;

interface UpdateInterface
{
    public function getChatId(): int;
}
