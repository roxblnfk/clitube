<?php

declare(strict_types=1);

namespace Roxblnfk\CliTube\Internal\Events;

use Psr\EventDispatcher\ListenerProviderInterface;

final class ListenerProvider implements ListenerProviderInterface
{
    public function __construct(
        private Subscriber $listeners,
    ) {
    }

    /**
     * @return iterable<int, callable>
     */
    public function getListenersForEvent(object $event): iterable
    {
        yield from $this->listeners->getForEvents(\get_class($event));
        /** @psalm-suppress MixedArgument */
        yield from $this->listeners->getForEvents(...\array_values(\class_parents($event)));
        /** @psalm-suppress MixedArgument */
        yield from $this->listeners->getForEvents(...\array_values(\class_implements($event)));
    }
}
