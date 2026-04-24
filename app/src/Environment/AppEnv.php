<?php

namespace App\Environment;

class AppEnv
{
    public function __construct(
        private string $env
    ) {}

    public function isProd(): bool
    {
        return $this->env === 'prod';
    }
}
