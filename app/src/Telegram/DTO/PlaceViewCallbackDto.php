<?php

namespace App\Telegram\DTO;

use App\Attributes\AsMessengerCallback;

#[AsMessengerCallback('/^place_view:(\d+)$/')]
class PlaceViewCallbackDto
{
    public function __construct(
        public readonly int $placeId
    ) {}
}
