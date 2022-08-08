<?php

declare(strict_types=1);

namespace Roxblnfk\CliTube\Screen;

use Symfony\Component\Console\Output\OutputInterface;

final class Leaflet extends AbstractScreen
{
    public bool $breakLines = true;

    /** @var array{0: int, 1: int, 2: int, 3: int} [Line, Column, end line, end column] in the buffer */
    private array $cursor = [0, 0, 0, 0];
    private ?string $pageStatus = null;

    private ?array $frameCache = null;
    private ?\Closure $pageStatusCallable = null;

    public function __construct(OutputInterface $output)
    {
        parent::__construct($output);
    }

    public function clear(bool $cleanInputLine = true): void
    {
        if (!$this->overwrite && !$this->firstDraw) {
            $this->removeLines(1, false);
        } else {
            parent::clear($cleanInputLine);
        }
    }

    /**
     * @param null|callable(self $sreen):string $callable
     */
    public function pageStatusCallable(?callable $callable): void
    {
        $this->pageStatusCallable = $callable !== null ? $callable(...)->bindTo($this) : null;
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
        $this->frameCache = null;
        $this->prepareFrame();
    }

    public function isEnd(): bool
    {
        return \array_key_last($this->buffer) <= $this->cursor[2]
            && \strlen($this->buffer[\array_key_last($this->buffer)]) >= $this->cursor[3];
    }

    protected function prepareFrame(): array
    {
        if ($this->frameCache !== null) {
            return $this->frameCache;
        }
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
        // Render Status
        $this->pageStatus = $this->pageStatusCallable === null ? null : ($this->pageStatusCallable)($this);
        if ($this->pageStatus === null) {
            ++$maxHeight;
        }
        // Don't render blank lines after the document
        if ($this->overwrite) {
            $result = \array_merge($result, \array_fill(0, $maxHeight - \count($result), ''));
        }
        if ($this->pageStatus !== null && $this->getWindowHeight() > \count($result)) {
            $result[] = \mb_substr($this->pageStatus, 0, $maxLength);
        }

        return $result;
    }
}
