<?php

declare(strict_types=1);

namespace CliTube\Command\User;

use CliTube\Command\Support\Stoppable;
use CliTube\Contract\Command\UserCommand;

final class Quit implements UserCommand
{
    use Stoppable;

    public static function createFromInput(string $input): ?UserCommand
    {
        if (\in_array(\strtolower($input), ['exit', ':q'])) {
            return new self();
        }

        return null;
    }
}
