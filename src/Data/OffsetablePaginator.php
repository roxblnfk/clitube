<?php

declare(strict_types=1);

namespace Roxblnfk\CliTube\Data;

interface OffsetablePaginator extends Paginator
{
    /**
     * Set page size.
     *
     * @psalm-immutable
     */
    public function withOffset(int $limit): static;

    public function getOffset(): int;
}
