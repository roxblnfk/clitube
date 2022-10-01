<?php

declare(strict_types=1);

namespace CliTube\Contract\Pagination;

use Countable;
use IteratorAggregate;

/**
 * @psalm-type TLimit = positive-int
 * @template-extends IteratorAggregate<array<array-key, scalar>>
 *
 * @method int count() Get current page items count
 */
interface Paginator extends IteratorAggregate, Countable
{
    /**
     * Set page size.
     *
     * @param TLimit $limit
     *
     * @psalm-immutable
     */
    public function withLimit(int $limit): static;

    /**
     * Get page size. The method won't be called before {@see withLimit()}.
     *
     * @return TLimit
     */
    public function getLimit(): int;

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
