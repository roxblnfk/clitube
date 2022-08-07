<?php

declare(strict_types=1);

namespace Roxblnfk\CliTube\Contract;

interface EventSubscriber
{
    public const PREPEND = 0;
    public const APPEND = 1;

    public function subscribeCallable(callable $listener, int $mode = self::PREPEND, string ...$events): void;

    public function subscribeComponent(Component $component): void;

    public function unsubscribeComponent(Component $component): void;
}
