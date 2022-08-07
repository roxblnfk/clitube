<?php

declare(strict_types=1);

namespace Roxblnfk\CliTube\Component;

use Roxblnfk\CliTube\Command\Core\CloseComponent;
use Roxblnfk\CliTube\Command\User\Quit;
use Roxblnfk\CliTube\Contract\Command\UserCommand;
use Roxblnfk\CliTube\Contract\CommandComponent;
use Roxblnfk\CliTube\Contract\InteractiveComponent;
use Roxblnfk\CliTube\Internal\Events\EventDispatcher;
use Roxblnfk\CliTube\Screen\Leaflet;

class Help implements CommandComponent, InteractiveComponent
{
    public function __construct(
        private readonly Leaflet $screen,
        private readonly EventDispatcher $eventDispatcher,
    ) {
        $this->generateText();
        $this->screen->redraw();
    }

    public function interact(UserCommand $command): void
    {
        if ($command instanceof Quit) {
            $this->exit($command);
        } else {
            $this->screen->interact($command);
        }
    }

    public function commandList(): iterable
    {
        yield from [
            Quit::class => $this->exit(...),
        ];
        yield from $this->screen->commandList();
    }

    private function generateText()
    {
        for ($i = 0; $i < 30; ++$i) {
            $this->screen->writeln($i . \str_repeat(' Foo Bar Baz', $i));
        }
    }

    private function exit(Quit $quit): void
    {
        $this->screen->clear();
        $quit->stopPropagation();
        $this->eventDispatcher->dispatch(new CloseComponent(component: $this));
    }
}
