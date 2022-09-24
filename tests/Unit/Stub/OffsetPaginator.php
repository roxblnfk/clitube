<?php

declare(strict_types=1);

namespace Roxblnfk\CliTube\Tests\Unit\Stub;

use Roxblnfk\CliTube\Data\OffsetPaginator as PaginatorInterface;
use Traversable;

class OffsetPaginator implements PaginatorInterface {
    private const ITEMS_COUNT = 12;
    /** @var positive-int */
    private int $page = 1;
    /** @var positive-int */
    private int $limit = 1;
    /** @var positive-int */
    private int $pages = 1;
    /** @var positive-int|0 */
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
        $this->limit = $limit;
        $this->calcProperties();
        return $this;
    }

    public function nextPage(): static
    {
        $this->page = \min($this->pages, $this->page + 1);
        $this->calcProperties();
        // var_dump((array)$this); die;
        return $this;
    }

    public function previousPage(): static
    {
        $this->page = \max(1, $this->page - 1);
        $this->calcProperties();
        return $this;
    }

    public function count(): int
    {
        return self::ITEMS_COUNT - ($this->page - 1) * $this->limit;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function withOffset(int $offset): static
    {
        $this->page = \max(1, (int)\ceil($offset / $this->limit));
        $this->calcProperties();
        return $this;
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
