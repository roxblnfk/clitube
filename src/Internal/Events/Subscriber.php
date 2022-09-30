<?php

declare(strict_types=1);

namespace Roxblnfk\CliTube\Internal\Events;

use Closure;
use ReflectionClass;
use ReflectionException;
use ReflectionFunction;
use ReflectionNamedType;
use ReflectionUnionType;
use Roxblnfk\CliTube\Command\Core\CloseComponent;
use Roxblnfk\CliTube\Contract\EventListener;
use Roxblnfk\CliTube\Contract\Component;
use Roxblnfk\CliTube\Contract\EventSubscriber;
use SplObjectStorage;

final class Subscriber implements EventSubscriber
{
    /**
     * @var callable[][]
     */
    private array $listeners = [];

    /** @var SplObjectStorage<Component, array> */
    private SplObjectStorage $components;

    public function __construct() {
        $this->components = new SplObjectStorage();
    }

    /**
     * Get listeners for event class names specified.
     *
     * @param string ...$eventClassNames Event class names.
     *
     * @return iterable<callable> Listeners.
     */
    public function getForEvents(string ...$eventClassNames): iterable
    {
        foreach ($eventClassNames as $eventClassName) {
            if (isset($this->listeners[$eventClassName])) {
                yield from $this->listeners[$eventClassName];
            }
        }
    }

    public function subscribeCallable(callable $listener, int $mode = self::PREPEND, string ...$events): void
    {
        if ($events === []) {
            $events = $this->getEventClasses($listener);
        }

        foreach ($events as $eventClassName) {
            $this->listeners[$eventClassName] ??= [];
            if ($mode === self::PREPEND) {
                \array_unshift($this->listeners[$eventClassName], $listener);
            } else {
                $this->listeners[$eventClassName][] = $listener;
            }
        }
    }

    public function subscribeComponent(Component $component): void
    {
        if ($component instanceof EventListener) {
            foreach ($component->getListeners() as $commandClass => $listener) {
                $this->subscribeCallable(
                    $listener,
                    EventSubscriber::PREPEND,
                    ...(\is_int($commandClass) ? [] : (array)$commandClass)
                );
            }
        }
    }

    public function unsubscribeComponent(Component $component): void
    {
        if (!$this->components->offsetExists($component)) {
            return;
        }

        foreach ($this->components->offsetGet($component) as $eventClass => $listeners) {
            foreach ($this->listeners[$eventClass] as $id => $listener) {
                if (\in_array($listener, $listeners, true)) {
                    unset($this->listeners[$eventClass][$id]);
                }
            }
        }
        $this->components->detach($component);
    }

    /**
     * Derives the interface type of the first argument of a callable.
     *
     * @return class-string[] Events classes.
     *
     * @throws ReflectionException
     */
    private function getEventClasses(callable $callable): array
    {
        $reflection = new ReflectionFunction(Closure::fromCallable($callable));
        if ($reflection->getNumberOfParameters() === 0) {
            return [];
        }
        $reflectedType = $reflection->getParameters()[0]->getType();

        if ($reflectedType instanceof ReflectionNamedType) {
            return [$reflectedType->getName()];
        }

        if ($reflectedType instanceof ReflectionUnionType) {
            $types = $reflectedType->getTypes();
            return \array_map(
                static fn(ReflectionNamedType $type) => $type->getName(),
                $types,
            );
        }

        return [];
    }
}
