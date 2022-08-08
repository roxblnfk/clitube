<?php

declare(strict_types=1);

namespace Roxblnfk\CliTube\Command\User;

use Roxblnfk\CliTube\Command\Support\Stoppable;
use Roxblnfk\CliTube\Contract\Command\UserCommand;

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
