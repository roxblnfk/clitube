<?php

declare(strict_types=1);

namespace CliTube\Command\User;

use CliTube\Command\Support\Stoppable;
use CliTube\Contract\Command\UserCommand;

/**
 * Any input
 */
final class Any implements UserCommand
{
    use Stoppable;

    public function __construct(
        public readonly string $input,
    ) {
    }

    public static function createFromInput(string $input): UserCommand
    {
        return new self($input);
    }
}
