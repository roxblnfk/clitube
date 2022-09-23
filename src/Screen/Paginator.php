<?php

declare(strict_types=1);

namespace Roxblnfk\CliTube\Screen;

use Roxblnfk\CliTube\Data\Paginator as PaginatorInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\BufferedOutput;

final class Paginator extends AbstractScreen
{
    private ?array $frameCache = null;
    private PaginatorInterface $paginator;
    /** @var array<int, string> */
    private array $tableLines = [];
    private int $lineOffset = 0;
    private ?\Closure $pageStatusCallable = null;

    /**
     * Get possible limit value for the paginator based on screen size and rendering template.
     *
     * @return positive-int Limit value for paginator
     */
    public function getBodySize(): int
    {
        return \max(1, $this->getWindowHeight() - 7);
    }

    public function showNext(): void
    {
        // Set new Offset
        $screenLength = $this->getWindowWidth();
        $longestLine = \max(\array_map($this->strlen(...), $this->tableLines));
        $maxOffset = $longestLine - $screenLength;
        if ($maxOffset <= $this->lineOffset) {
            $this->lineOffset = 0;
        } else {
            $this->lineOffset = \min($maxOffset, $this->lineOffset + (int)\ceil($screenLength * .3));
        }
        // Redraw cached frame
        $this->frameCache = null;
        $this->prepareFrame();
    }

    public function setPaginator(PaginatorInterface $paginator): void
    {
        $this->paginator = $paginator;
        $this->tableLines = \explode("\n", $this->renderTable());
        $this->frameCache = null;
        $this->prepareFrame();
    }

    /**
     * @param null|callable(self $sreen):string $callable
     */
    public function pageStatusCallable(?callable $callable): void
    {
        $this->pageStatusCallable = $callable !== null ? $callable(...)->bindTo($this) : null;
    }

    protected function prepareFrame(): array
    {
        if ($this->frameCache !== null) {
            return $this->frameCache;
        }
        $maxLength = $this->getWindowWidth();
        $maxHeight = \max(1, $this->getWindowHeight() - 2);
        $result = [];
        // [$line, $column] = $this->cursor;
        foreach ($this->tableLines as $line) {
            $result[] = $this->substr($line, $this->lineOffset, $maxLength);
        }

        // Render Status and Input
        $this->pageStatus = (string)($this->pageStatusCallable === null ? null : ($this->pageStatusCallable)($this));
        $this->pageInput = $this->renderPaginatorBar() . '  ';

        // Render blank lines after the table
        $result = \array_merge($result, \array_fill(0, $maxHeight - \count($result), ''));

        return $result;
    }

    protected function renderTable(): string
    {
        $output = new BufferedOutput(formatter: $this->output->getFormatter());
        $data = [];
        $headers = null;
        foreach ($this->paginator as $line) {
            $headers ??= \array_keys($line);
            $data[] = $line;
        }
        (new Table($output))
            ->setHeaders($headers)
            ->setRows($data)
            ->render();

        return $output->fetch();
    }

    protected function renderPaginatorBar(): string
    {
        return '1 2 3';
    }
}
