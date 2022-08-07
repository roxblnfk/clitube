<?php

declare(strict_types=1);

namespace Roxblnfk\CliTube\Command\Core;

use Roxblnfk\CliTube\Contract\Command\CoreCommand;
use Roxblnfk\CliTube\Contract\Component;

final class CloseComponent implements CoreCommand
{
    public function __construct(
        public Component $component,
    ) {
    }
}
