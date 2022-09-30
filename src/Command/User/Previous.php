<?php

declare(strict_types=1);

namespace Roxblnfk\CliTube\Command\User;

use Roxblnfk\CliTube\Command\Support\Stoppable;
use Roxblnfk\CliTube\Contract\Command\UserCommand;

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
