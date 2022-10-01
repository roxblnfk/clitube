<?php

declare(strict_types=1);

namespace CliTube\Command\Core;

use CliTube\Contract\Command\CoreCommand;
use CliTube\Contract\Component;

final class CloseComponent implements CoreCommand
{
    public function __construct(
        public Component $component,
    ) {
    }
}
