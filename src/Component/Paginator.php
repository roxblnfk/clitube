<?php

declare(strict_types=1);

namespace Roxblnfk\CliTube\Component;

use Roxblnfk\CliTube\Contract\Component;
use Roxblnfk\CliTube\Screen\Paginator;
use Symfony\Component\Console\Output\OutputInterface;

class Paginator implements Component
{
    private Paginator $screen;

    public function __construct(
        private OutputInterface $output,
        ?Paginator $screen
    ) {
        $this->screen = $screen ?? new Paginator($output);
    }

    public function redraw(): void
    {

    }
}
