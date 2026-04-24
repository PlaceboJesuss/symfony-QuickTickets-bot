<?php

namespace App\DTO;

class ButtonDto
{
    public function __construct(
        public readonly string $text,
        public readonly string $payload
    ) {}
}
