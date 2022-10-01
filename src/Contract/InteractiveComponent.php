<?php

declare(strict_types=1);

namespace CliTube\Contract;

use CliTube\Contract\Command\UserCommand;

/**
 * Means the component can interact with user using input commands.
 */
interface InteractiveComponent extends Component
{
    public function interact(UserCommand $command): void;

    public function getUserCommands(): iterable;
}
