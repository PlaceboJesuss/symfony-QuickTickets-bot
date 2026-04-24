<?php

namespace App\Resolver;

use App\Attributes\AsMessengerCommand;
use App\DTO\MessengerContextDto;
use App\Interfaces\CommandHandlerInterface;
use App\Telegram\DTO\MessageUpdateDto;

class CommandResolver
{
    /** @var array<array{pattern: string, handler: CommandHandlerInterface}> */
    private array $handlers = [];

    public function __construct(iterable $handlers)
    {
        foreach ($handlers as $handler) {
            $reflection = new \ReflectionClass($handler);
            $attributes = $reflection->getAttributes(AsMessengerCommand::class);

            foreach ($attributes as $attribute) {
                $attr = $attribute->newInstance();

                $this->handlers[] = [
                    'pattern' => $attr->pattern,
                    'handler' => $handler,
                    'priority' => $attr->priority,
                ];
            }
        }

        // сортировка по приоритету
        usort($this->handlers, fn($a, $b) => $b['priority'] <=> $a['priority']);
    }

    public function resolve(
        MessengerContextDto $context,
        MessageUpdateDto $update
    ): void {
        $text = $update->getText();

        foreach ($this->handlers as $item) {
            if (preg_match($item['pattern'], $text, $matches)) {
                ($item['handler'])($context, $update, $matches);
                return;
            }
        }

        // fallback handler (можно сделать DefaultHandler)
        throw new \RuntimeException("Command not found: $text");
    }
}
