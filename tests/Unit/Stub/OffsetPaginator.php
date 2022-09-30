<?php

declare(strict_types=1);

namespace Roxblnfk\CliTube\Tests\Unit\Stub;

use Roxblnfk\CliTube\Data\OffsetPaginator as PaginatorInterface;
use Traversable;

class OffsetPaginator implements PaginatorInterface {
    private const ITEMS_COUNT = 150;
    /** @var positive-int */
    private int $page = 1;
    /** @var positive-int */
    private int $limit = 1;
    /** @var positive-int */
    private int $pages = 1;
    /** @var int<0, max> */
    private int $offset = 0;

    public function __construct()
    {
        $this->calcProperties();
    }

    public function getIterator(): Traversable
    {
        $count = \min($this->limit, self::ITEMS_COUNT - ($this->page - 1) * $this->limit);
        for ($i = 1; $i <= $count; ++$i) {
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
                "FFFFOOOO-$this->page-$i",
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

    public function withLimit(int $limit): static
    {
        $clone = clone $this;
        $clone->limit = $limit;
        $clone->calcProperties();
        return $clone;
    }

    public function withOffset(int $offset): static
    {
        $clone = clone $this;
        $page = (int)\ceil($offset / $clone->limit) + 1;
        \assert($page > 0);
        $clone->page = $page;
        $clone->calcProperties();
        return $clone;
    }

    public function nextPage(): static
    {
        $clone = clone $this;
        $clone->page = \min($clone->pages, $clone->page + 1);
        $clone->calcProperties();
        return $clone;
    }

    public function previousPage(): static
    {
        $clone = clone $this;
        $clone->page = \max(1, $clone->page - 1);
        $clone->calcProperties();
        return $clone;
    }

    public function count(): int
    {
        return self::ITEMS_COUNT - ($this->page - 1) * $this->limit;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function getCount(): int
    {
        return self::ITEMS_COUNT;
    }

    private function calcProperties(): void
    {
        $this->pages = \max(1, (int)\ceil(self::ITEMS_COUNT / $this->limit));
        $this->page = \min($this->page, $this->pages);
        $this->offset = ($this->page - 1) * $this->limit;
    }
}
