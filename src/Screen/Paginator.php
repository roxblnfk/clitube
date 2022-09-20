<?php

declare(strict_types=1);

namespace Roxblnfk\CliTube\Screen;

use Roxblnfk\CliTube\Data\PaginatorInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\BufferedOutput;

final class Paginator extends AbstractScreen
{
    private ?array $frameCache = null;
    private PaginatorInterface $paginator;
    /** @var array<int, string> */
    private array $tableLines = [];
    private int $lineOffset = 0;

    /**
     * Get possible limit value for the paginator based on screen size and rendering template.
     *
     * @return int Limit value for paginator
     */
    public function getBodySize(): int
    {
        return $this->getWindowHeight() - 7;
    }

    public function showNext(): void
    {
        // Set new Offset
        $screenLength = $this->getWindowWidth();
        $longestLine = \max(\array_map('mb_strlen', $this->tableLines));
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
            $result[] = \mb_substr($line, $this->lineOffset, $maxLength);
        }

        // Render Status
        // $this->pageStatus = $this->pageStatusCallable === null ? null : ($this->pageStatusCallable)($this);
        // if ($this->pageStatus === null) {
        //     ++$maxHeight;
        // }
        // Don't render blank lines after the document
        // if ($this->overwrite) {
        //     $result = \array_merge($result, \array_fill(0, $maxHeight - \count($result), ''));
        // }
        // if ($this->pageStatus !== null && $this->getWindowHeight() > \count($result)) {
        //     $result[] = \mb_substr($this->pageStatus, 0, $maxLength);
        // }

        return $result;
    }

    private function renderTable(): string
    {
        $output = new BufferedOutput();
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
}
