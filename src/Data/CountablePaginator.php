<?php

declare(strict_types=1);

namespace Roxblnfk\CliTube\Data;

interface CountablePaginator extends Paginator
{
    /**
     * @return positive-int|0 Count of all items
     */
    public function getCount(): int;
}
