<?php

declare(strict_types=1);

namespace Roxblnfk\CliTube\Data;

interface OffsetPaginator extends Paginator
{
    /**
     * Set page size.
     *
     * @param positive-int|0 $offset
     *
     * @psalm-immutable
     */
    public function withOffset(int $offset): static;

    /**
     * @return positive-int|0
     */
    public function getOffset(): int;

    /**
     * @return positive-int|0 Count of all items
     */
    public function getCount(): int;
}
