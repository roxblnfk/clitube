<?php

declare(strict_types=1);

namespace CliTube\Contract\Event;

use CliTube\Contract\Component;

interface EventListener extends Component
{
    /**
     * @return iterable<class-string, callable> Event and related listener.
     */
    public function getListeners(): iterable;
}
