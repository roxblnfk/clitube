<?php

declare(strict_types=1);

namespace Roxblnfk\CliTube\Command\User;

use Roxblnfk\CliTube\Command\Support\Stoppable;
use Roxblnfk\CliTube\Contract\Command\UserCommand;

/**
 * Any input
 */
final class Next implements UserCommand
{
    use Stoppable;

    public string $input;

    public function __construct(
        public readonly bool $toEnd = false,
    ) { }

    public static function createFromInput(string $input): ?UserCommand
    {
        if (\preg_match('/^>>?$/', $input)) {
            return new self($input === '>>');
        }

        return null;
    }
}
