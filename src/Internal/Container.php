<?php

declare(strict_types=1);

namespace Roxblnfk\CliTube\Internal;

use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;
use Yiisoft\Injector\ArgumentException;
use Yiisoft\Injector\Injector;

final class Container implements ContainerInterface
{
    private array $values = [];

    /**
     * @template T
     * @param class-string<T> $id
     * @return T
     * @psalm-return ($id is class-string ? T : mixed)
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function get(string $id)
    {
        if ($id === ContainerInterface::class) {
            return $this;
        }
        try {
            return $this->has($id)
                ? $this->values[$id]
                : ($this->values[$id] = $this->make($id));
        } catch (ArgumentException $e) {
            throw new class ($id, $e) extends RuntimeException implements NotFoundExceptionInterface {
                public function __construct(string $id, \Throwable $exception)
                {
                    parent::__construct("`$id` not found.", previous: $exception);
                }
            };
        }
    }

    public function has(string $id): bool
    {
        return $id === ContainerInterface::class || \array_key_exists($id, $this->values);
    }

    /**
     * @template T
     * @param class-string<T> $id
     * @return T
     */
    public function make(string $id, array $arguments = []): object
    {
        return (new Injector($this))->make($id, $arguments);
    }

    public function invoke(callable $callable, array $arguments = []): object
    {
        return (new Injector($this))->invoke($callable, $arguments);
    }

    public function set(string $id, mixed $value): self
    {
        $this->values[$id] = $value;
        if (\is_object($value) && $id !== $value::class) {
            $this->values[$value::class] = $value;
        }
        return $this;
    }

    public function setObject(object $value): self
    {
        $this->values[$value::class] = $value;
        return $this;
    }
}
