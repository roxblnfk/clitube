<?php

declare(strict_types=1);

namespace CliTube\Contract\Pagination;

interface OffsetPaginator extends Paginator
{
    /**
     * Set page size.
     *
     * @param int<0, max> $offset
     *
     * @psalm-immutable
     */
    public function withOffset(int $offset): static;

    /**
     * @return int<0, max>
     */
    public function getOffset(): int;

    /**
     * @return int<0, max> Count of all items
     */
    public function getCount(): int;
}
