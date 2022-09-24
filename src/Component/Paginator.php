<?php

declare(strict_types=1);

namespace Roxblnfk\CliTube\Component;

use Closure;
use Roxblnfk\CliTube\Command\Core\CloseComponent;
use Roxblnfk\CliTube\Command\User\Next;
use Roxblnfk\CliTube\Command\User\Noop;
use Roxblnfk\CliTube\Command\User\Previous;
use Roxblnfk\CliTube\Command\User\Quit;
use Roxblnfk\CliTube\Contract\Command\UserCommand;
use Roxblnfk\CliTube\Contract\InteractiveComponent;
use Roxblnfk\CliTube\Data\OffsetPaginator;
use Roxblnfk\CliTube\Data\Paginator as PaginatorInterface;
use Roxblnfk\CliTube\Internal\Events\EventDispatcher;
use Roxblnfk\CliTube\Screen\Paginator as PaginatorScreen;

class Paginator implements InteractiveComponent
{

    public function __construct(
        private readonly PaginatorScreen $screen,
        private readonly EventDispatcher $eventDispatcher,
        private PaginatorInterface $paginator,
    ) {
        $this->configureScreen();
        $this->configurePaginator();
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

    public function setPaginator(PaginatorInterface $paginator): static
    {
        $this->paginator = $paginator;
        return $this;
    }

    protected function redraw(bool $onlyInput = false): void
    {
        $this->screen->setPaginator($this->paginator);
        $this->screen->redraw(true);
    }

    /**
     * @return array<class-string<UserCommand>, Closure>
     */
    protected function getCallables(): array
    {
        return [
            Quit::class => $this->exit(...),
            Next::class => $this->nextPage(...),
            Previous::class => $this->previousPage(...),
            Noop::class => $this->screen->showNext(...),
        ];
    }

    protected function configurePaginator(): void
    {
        $this->paginator = $this->paginator->withLimit($this->screen->getBodySize());
    }

    protected function configureScreen(): void
    {
        $tips = [
            'Press Enter to scroll the content horizontally',
            'Enter `<` or `>` to change the current page',
        ];
        if ($this->paginator instanceof OffsetPaginator) {
            $tips[] = 'Enter `<<` or `>>` to navigate oin the firs or the last page';
        }
        $this->screen->pageStatusCallable(static fn (): string => \sprintf(
            "\033[90m%s\033[0m",
            $tips[\array_rand($tips)],
        ));
    }

    protected function exit(?Quit $event = null): void
    {
        $this->screen->clear();
        $event?->stopPropagation();
        $this->eventDispatcher->dispatch(new CloseComponent(component: $this));
    }

    protected function nextPage(?Next $event = null): void
    {
        $this->paginator = $this->paginator->nextPage();
        $this->redraw();
    }

    protected function previousPage(?Previous $event = null): void
    {
        $this->paginator = $this->paginator->previousPage();
        $this->redraw();
    }
}
