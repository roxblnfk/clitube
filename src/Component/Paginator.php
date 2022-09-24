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
            Previous::class => $this->previousPage(...),
            Noop::class => $this->screen->showNext(...),
        ];
    }

    private function configurePaginator(): void
    {
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

    private function previousPage(?Previous $event = null): void
    {
        $this->paginator = $this->paginator->previousPage();
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
