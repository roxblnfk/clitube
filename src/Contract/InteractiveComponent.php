<?php

declare(strict_types=1);

namespace Roxblnfk\CliTube\Contract;

use Roxblnfk\CliTube\Contract\Command\UserCommand;

interface InteractiveComponent extends Component
{
    public function interact(UserCommand $command): void;

    public function getUserCommands(): iterable;
}
