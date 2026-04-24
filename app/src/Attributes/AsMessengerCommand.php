<?php

namespace App\Attributes;

#[\Attribute(\Attribute::TARGET_CLASS)]
class AsMessengerCommand
{
    public function __construct(
        public string $pattern,
        public int $priority = 0
    ) {
    }
}
