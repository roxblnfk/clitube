<?php

declare(strict_types=1);

namespace Roxblnfk\CliTube\Component;

use Closure;
use Roxblnfk\CliTube\Command\Core\CloseComponent;
use Roxblnfk\CliTube\Command\User\Noop;
use Roxblnfk\CliTube\Command\User\Quit;
use Roxblnfk\CliTube\Contract\Command\UserCommand;
use Roxblnfk\CliTube\Contract\InteractiveComponent;
use Roxblnfk\CliTube\Internal\Events\EventDispatcher;
use Roxblnfk\CliTube\Screen\Leaflet;

class Scroll implements InteractiveComponent
{
    public function __construct(
        private readonly Leaflet $screen,
        private readonly EventDispatcher $eventDispatcher,
    ) {
        $this->generateText();
        $this->configureScreen();
        $this->redraw();
    }

    public function interact(UserCommand $command): void
    {
        $callable = $this->getCallables()[$command::class] ?? null;
        if ($callable === null) {
            $this->redraw(true);
        } else {
            $callable($command);
            $this->redraw();
        }

        if (!$this->screen->overwrite && $this->screen->isEnd()) {
            $this->exit();
        }
    }

    public function getUserCommands(): iterable
    {
        return \array_keys($this->getCallables());
    }

    private function redraw(bool $onlyInput = false): void
    {
        if ($onlyInput) {
            $this->screen->clear(true);
        } else {
            $this->screen->redraw(true);
        }
    }

    /**
     * @return array<class-string<UserCommand>, Closure>
     */
    private function getCallables(): array
    {
        return [
            Quit::class => $this->exit(...),
            Noop::class => $this->screen->goToNext(...),
        ];
    }

    private function generateText()
    {
        for ($i = 0; $i < 30; ++$i) {
            $this->screen->writeln($i . \str_repeat(' Foo Bar Baz', $i));
        }
    }

    private function exit(?Quit $event = null): void
    {
        $this->screen->clear();
        $event?->stopPropagation();
        $this->eventDispatcher->dispatch(new CloseComponent(component: $this));
    }

    private function configureScreen()
    {
        // $this->screen->overwrite = false;
        $this->screen->pageStatusCallable(fn (Leaflet $screen) =>
            \sprintf(
                "\033[90m%s\033[0m",
                \rtrim(\str_pad(
                    $screen->isEnd() ? '-- End --' : "-- Press \033[06m Enter \033[0m\033[90m to continue --",
                    $screen->getWindowWidth(),
                    ' ',
                    \STR_PAD_BOTH,
                ), ' ')
            )
        );
    }
}
