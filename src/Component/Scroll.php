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
use Stringable;

class Scroll implements InteractiveComponent
{
    /**
     * @param iterable<array-key, string>|string|Stringable $content
     * @param bool $overwrite If {@see true} then only one screen will be used.
     *        Each next slide will overwrite the previous one.
     */
    public function __construct(
        private readonly Leaflet $screen,
        private readonly EventDispatcher $eventDispatcher,
        iterable|string|Stringable $content,
        bool $overwrite = true,
    ) {
        $this->generateText($content);
        $this->configureScreen($overwrite);
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

    /**
     * @param iterable<array-key, string>|string|Stringable $content
     */
    private function generateText(iterable|string|Stringable $content): void
    {
        if (\is_iterable($content)) {
            foreach ($content as $string) {
                $this->screen->writeln($string);
            }
            return;
        }
        $this->screen->write((string)$content);
    }

    private function exit(?Quit $event = null): void
    {
        $this->screen->clear();
        $event?->stopPropagation();
        $this->eventDispatcher->dispatch(new CloseComponent(component: $this));
    }

    private function configureScreen(bool $overwrite): void
    {
        $this->screen->overwrite = $overwrite;
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
