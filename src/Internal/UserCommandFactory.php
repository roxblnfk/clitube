<?php

declare(strict_types=1);

namespace CliTube\Internal;

use CliTube\Command\User\Any;
use CliTube\Contract\Command\UserCommand;

final class UserCommandFactory
{
    /**
     * @param iterable<int, class-string<UserCommand>> $classes
     */
    public function create(iterable $classes, string $input): UserCommand
    {
        $input = \trim($input);
        foreach ($classes as $class) {
            $command = $class::createFromInput($input);
            if ($command !== null) {
                return $command;
            }
        }
        return Any::createFromInput($input);
    }
}
