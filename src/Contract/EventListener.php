<?php

declare(strict_types=1);

namespace CliTube\Contract;

use Generator;
use CliTube\Contract\Command\Command;

interface EventListener extends Component
{
    /**
     * @return iterable<class-string, callable> Event and related listener.
     */
    public function getListeners(): iterable;
}
