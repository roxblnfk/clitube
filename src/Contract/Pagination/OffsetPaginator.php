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
     * The method won't be called before {@see withOffset()}.
     *
     * @return int<0, max>
     */
    public function getOffset(): int;

    /**
     * Count of all items
     *
     * @return int<0, max>|null
     */
    public function getCount(): ?int;
}
