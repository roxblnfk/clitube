<?php

declare(strict_types=1);

namespace CliTube\Support\Pagination;

use ArrayIterator;
use Countable;
use InvalidArgumentException;
use Traversable;

/**
 * @internal
 */
class IterablePaginator extends BaseOffsetPaginator
{
    protected Traversable $data;

    public function __construct(iterable|object $data)
    {
        $this->data = match (true) {
            $data instanceof Traversable => $data,
            \is_array($data) || \is_object($data) => new ArrayIterator($data),
            default => throw new InvalidArgumentException('Unsupported iterable value.'),
        };
        if ($this->data instanceof Countable) {
            $this->count = \count($data);
        }
    }

    public function __clone(): void
    {
        $this->data = clone $this->data;
    }

    protected function getContent(): array
    {
        if ($this->buffer === null) {
            // Jump to offset
            $this->data->seek($this->offset);
            $this->buffer = [];
            for ($i = 0; $i < $this->limit && $this->data->valid(); ++$i) {
                $this->buffer[] = $this->data->current();
                $this->data->next();
            }
        }
        return $this->buffer;
    }
}
