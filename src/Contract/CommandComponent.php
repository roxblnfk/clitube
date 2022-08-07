<?php

declare(strict_types=1);

namespace Roxblnfk\CliTube\Contract;

use Generator;
use Roxblnfk\CliTube\Contract\Command\Command;

interface CommandComponent extends Component
{
    /**
     * Get supported command list.
     *
     * @return iterable<class-string<Command>, callable> Command and related listener.
     */
    public function commandList(): iterable;
}
