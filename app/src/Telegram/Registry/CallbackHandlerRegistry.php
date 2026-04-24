<?php

namespace App\Telegram\Registry;

class CallbackHandlerRegistry
{
    private array $map = [];

    public function register(string $dtoClass, callable $handler): void
    {
        $this->map[$dtoClass] = $handler;
    }

    public function handle(object $dto, Update $update): void
    {
        $class = $dto::class;

        if (!isset($this->map[$class])) {
            return;
        }

        ($this->map[$class])($update, $dto);
    }
}
