<?php

declare(strict_types=1);

namespace CliTube\Contract\Event;

use CliTube\Contract\Component;

interface EventSubscriber
{
    public const PREPEND = 0;
    public const APPEND = 1;

    /**
     * Attaches listener to corresponding event based on the type-hint used for the event argument.
     *
     * @param class-string ...$events
     */
    public function subscribeCallable(callable $listener, int $mode = self::PREPEND, string ...$events): void;

    public function subscribeComponent(Component $component): void;

    public function unsubscribeComponent(Component $component): void;
}
