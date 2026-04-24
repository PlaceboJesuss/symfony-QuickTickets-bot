<?php

namespace App\Resolver;

use App\Attributes\AsMessengerCallback;
use App\DTO\MessengerContextDto;
use App\Interfaces\CallbackHandlerInterface;
use App\Telegram\DTO\CallbackQueryUpdateDto;

class CallbackResolver
{
    /** @var array<array{pattern: string, handler: CallbackHandlerInterface}> */
    private array $handlers = [];

    public function __construct(iterable $handlers)
    {
        foreach ($handlers as $handler) {
            $reflection = new \ReflectionClass($handler);
            $attributes = $reflection->getAttributes(AsMessengerCallback::class);

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
        CallbackQueryUpdateDto $update
    ): void {
        $data = $update->getData();

        foreach ($this->handlers as $item) {
            if (preg_match($item['pattern'], $data, $matches)) {
                ($item['handler'])($context, $update, $matches);
                return;
            }
        }

        // fallback (опционально)
        throw new \RuntimeException("Callback handler not found for: $data");
    }
}
