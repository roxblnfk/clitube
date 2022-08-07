<?php

declare(strict_types=1);

namespace Roxblnfk\CliTube\Command\User;

use Roxblnfk\CliTube\Command\Support\Stoppable;
use Roxblnfk\CliTube\Contract\Command\UserCommand;

final class Noop implements UserCommand
{
    use Stoppable;

    public static function createFromInput(string $input): ?UserCommand
    {
        return $input === '' ? new self() : null;
    }
}
