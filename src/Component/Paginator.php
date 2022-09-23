<?php

declare(strict_types=1);

namespace Roxblnfk\CliTube\Component;

use Closure;
use Roxblnfk\CliTube\Command\Core\CloseComponent;
use Roxblnfk\CliTube\Command\User\Next;
use Roxblnfk\CliTube\Command\User\Noop;
use Roxblnfk\CliTube\Command\User\Quit;
use Roxblnfk\CliTube\Contract\Command\UserCommand;
use Roxblnfk\CliTube\Contract\InteractiveComponent;
use Roxblnfk\CliTube\Data\Paginator as PaginatorInterface;
use Roxblnfk\CliTube\Internal\Events\EventDispatcher;
use Roxblnfk\CliTube\Screen\Paginator as PaginatorScreen;
use Traversable;

class Paginator implements InteractiveComponent
{
    private PaginatorInterface $paginator;

    public function __construct(
        readonly private PaginatorScreen $screen,
        private readonly EventDispatcher $eventDispatcher,
    ) {
        $this->configureScreen();
        $this->generateText();
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
    }

    public function getUserCommands(): iterable
    {
        return \array_keys($this->getCallables());
    }

    private function redraw(bool $onlyInput = false): void
    {
        $this->screen->setPaginator($this->paginator);
        $this->screen->redraw(true);
    }

    /**
     * @return array<class-string<UserCommand>, Closure>
     */
    private function getCallables(): array
    {
        return [
            Quit::class => $this->exit(...),
            Next::class => $this->nextPage(...),
            Noop::class => $this->screen->showNext(...),
        ];
    }

    private function generateText(): void
    {
        $this->paginator = new \Roxblnfk\CliTube\Tests\Unit\Stub\Paginator();
        $this->paginator = $this->paginator->withLimit($this->screen->getBodySize());
    }

    private function exit(?Quit $event = null): void
    {
        $this->screen->clear();
        $event?->stopPropagation();
        $this->eventDispatcher->dispatch(new CloseComponent(component: $this));
    }

    private function nextPage(?Next $event = null): void
    {
        $this->paginator = $this->paginator->nextPage();
        $this->redraw();
    }

    private function configureScreen(): void
    {
        // $this->screen->pageStatusCallable(fn (Leaflet $screen) => \sprintf(
        //     "\033[90m%s\033[0m",
        //     \rtrim(\str_pad(
        //         $screen->isEnd() ? '-- End --' : "-- Press \033[06m Enter \033[0m\033[90m to continue --",
        //         $screen->getWindowWidth(),
        //         ' ',
        //         \STR_PAD_BOTH,
        //     ), ' ')
        // ));
    }
}
