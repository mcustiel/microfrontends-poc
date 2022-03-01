<?php

declare(strict_types=1);

namespace Mcustiel\MicrofrontendsComposer\Collections;

use Countable;
use Iterator;

use function count;

abstract class AbstractArrayCollection implements Iterator, Countable
{
    /** @var array */
    protected $data;

    public function __construct()
    {
        $this->data = [];
    }

    public function current(): mixed
    {
        return current($this->data);
    }

    public function next(): void
    {
        next($this->data);
    }

    public function key(): string|int|null
    {
        return key($this->data);
    }

    public function valid(): bool
    {
        return key($this->data) !== null;
    }

    public function rewind(): void
    {
        reset($this->data);
    }

    public function isEmpty(): bool
    {
        return empty($this->data);
    }

    public function count(): int
    {
        return count($this->data);
    }
}
