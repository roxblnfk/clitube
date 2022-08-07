<?php

declare(strict_types=1);

namespace Roxblnfk\CliTube\Screen;

use Roxblnfk\CliTube\Command\User\Noop;
use Roxblnfk\CliTube\Contract\Command\UserCommand;
use Roxblnfk\CliTube\Contract\CommandComponent;
use Roxblnfk\CliTube\Contract\InteractiveComponent;
use Symfony\Component\Console\Output\OutputInterface;

final class Leaflet extends AbstractScreen implements CommandComponent, InteractiveComponent
{
    public bool $breakLines = true;

    /** @var array{0: int, 1: int, 2: int, 3: int} [Line, Column, end line, end column] in the buffer */
    private array $cursor = [0, 0, 0, 0];

    public function __construct(OutputInterface $output)
    {
        parent::__construct($output);
    }

    public function commandList(): iterable
    {
        return [Noop::class => $this->goToNext(...)];
    }

    public function interact(UserCommand $command): void
    {
        if ($command instanceof Noop) {
            $this->goToNext();
        }
        $this->redraw(true);
    }

    public function goToNext(): void
    {
        if ($this->isEnd()) {
            return;
        }
        if (\count($this->cursor) === 4) {
            $this->cursor = [$this->cursor[2], $this->cursor[3]];
        } else {
            $this->cursor = [\min(\count($this->buffer), $this->cursor[0] + 1), 0];
        }
    }

    protected function prepareFrame(): array
    {
        $maxLength = $this->getWindowWidth();
        $maxHeight = \max(1, $this->getWindowHeight() - 1);
        $result = [];
        [$line, $column] = $this->cursor;
        while (\count($result) < $maxHeight && isset($this->buffer[$line])) {
            if ($this->breakLines) {
                $result[] = \mb_substr($this->buffer[$line], $column, $maxLength);
                $column += $maxLength;
                if (\mb_strlen($this->buffer[$line]) - $column < 0) {
                    $column = 0;
                    ++$line;
                }
            } else {
                $result[] = \mb_substr($this->buffer[$line], $column, $maxLength);
                $column = 0;
                ++$line;
            }
            $this->cursor[2] = $line;
            $this->cursor[3] = $column;
        }
        if ($this->getWindowHeight() > \count($result)) {
            $result[] = $this->renderProgress();
        }

        return $result;
    }

    public function isEnd(): bool
    {
        return \array_key_last($this->buffer) <= $this->cursor[2]
            && \strlen($this->buffer[\array_key_last($this->buffer)]) >= $this->cursor[3];
    }

    protected function renderProgress(): string
    {
        return $this->wrapColor(
            $this->isEnd() ? '-- End --' : '-- Press Enter to continue --',
            90
        );
    }
}
