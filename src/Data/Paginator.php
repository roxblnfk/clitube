<?php

declare(strict_types=1);

namespace Roxblnfk\CliTube\Data;

use Countable;
use IteratorAggregate;

interface Paginator extends IteratorAggregate, Countable
{
    /**
     * Set page size.
     *
     * @psalm-immutable
     */
    public function withLimit(int $limit): static;

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
