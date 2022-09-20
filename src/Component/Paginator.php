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
use Roxblnfk\CliTube\Data\PaginatorInterface;
use Roxblnfk\CliTube\Internal\Events\EventDispatcher;
use Roxblnfk\CliTube\Screen\Paginator as PaginatorScreen;
use Symfony\Component\Console\Output\OutputInterface;
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

    private function generateText()
    {
        $this->paginator = new class implements PaginatorInterface {
            private int $page = 1;
            private int $limit = 1;

            public function getIterator(): Traversable
            {
                for ($i = 1; $i <= $this->limit; ++$i) {
                    yield [
                        "foo-$this->page-$i",
                        "bar-$this->page-$i",
                        "baz-$this->page-$i",
                        "fiz-$this->page-$i",
                        "lon-$this->page-$i",
                        "der-$this->page-$i",
                        "gat-$this->page-$i",
                        "lup-$this->page-$i",
                        "n-put-$this->page-$i",
                        "n-kez-$this->page-$i",
                        "n-dec-$this->page-$i",
                        "n-nlp-$this->page-$i",
                        "n-ced-$this->page-$i",
                        "n-cde-$this->page-$i",
                        "n-mew-$this->page-$i",
                        "n-gaw-$this->page-$i",
                        "n-cry-$this->page-$i",
                        "n-flo-$this->page-$i",
                        "n-dil-$this->page-$i",
                        "n-ddl-$this->page-$i",
                        "n-ddl-$this->page-$i",
                        "n-lap-$this->page-$i",
                        "n-paw-$this->page-$i",
                        "n-wap-$this->page-$i",
                    ];
                }
            }

            public function setLimit(int $limit): static
            {
                $this->limit = $limit;
                return $this;
            }

            public function nextPage(): static
            {
                ++$this->page;
                return $this;
            }

            public function previousPage(): static
            {
                --$this->page;
                return $this;
            }
        };
        $this->paginator = $this->paginator->setLimit($this->screen->getBodySize());
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

    private function configureScreen()
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
