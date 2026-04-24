<?php

namespace App\Telegram\CompilerPass;

use App\Attributes\AsMessengerCallback;
use App\Resolver\CallbackResolver;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CallbackPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(CallbackResolver::class)) {
            return;
        }

        $resolverDef = $container->findDefinition(CallbackResolver::class);

        foreach ($container->getDefinitions() as $id => $definition) {
            $class = $definition->getClass();

            if (!$class || !str_starts_with($class, 'App\\')) {
                continue;
            }

            $ref = new \ReflectionClass($class);
            $attrs = $ref->getAttributes(AsMessengerCallback::class);

            if (!$attrs) {
                continue;
            }

            $attr = $attrs[0]->newInstance();

            $resolverDef->addMethodCall('register', [
                $attr->pattern,
                $class
            ]);
        }
    }
}
