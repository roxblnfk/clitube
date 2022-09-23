<?php

declare(strict_types=1);

namespace Roxblnfk\CliTube\Tests\Unit\Stub;

use Roxblnfk\CliTube\Data\Paginator as PaginatorInterface;
use Traversable;

class Paginator implements PaginatorInterface {
    private int $page = 1;
    /** @var positive-int */
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
    public function count(): int
    {
        return $this->limit;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }
}
