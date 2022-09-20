<?php

declare(strict_types=1);

namespace Roxblnfk\CliTube\Data;

use IteratorAggregate;

interface PaginatorInterface extends IteratorAggregate
{
    /**
     * Set page size.
     *
     * @psalm-immutable
     */
    public function setLimit(int $limit): static;

    /**
     * Go to the next page.
     *
     * @psalm-immutable
     */
    public function nextPage(): static;

    /**
     * Go to the previous page.
     *
     * @psalm-immutable
     */
    public function previousPage(): static;
}
