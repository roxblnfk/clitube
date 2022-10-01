<?php

declare(strict_types=1);

namespace CliTube\Command\User;

use CliTube\Command\Support\Stoppable;
use CliTube\Contract\Command\UserCommand;

/**
 * Go to previous element
 */
final class Previous implements UserCommand
{
    use Stoppable;

    public function __construct(
        public readonly bool $toStart = false,
    ) {
    }

    public static function createFromInput(string $input): ?UserCommand
    {
        if (\preg_match('/^<<?$/', $input)) {
            return new self($input === '<<');
        }

        return null;
    }
}
