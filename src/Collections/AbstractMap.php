<?php

declare(strict_types=1);

namespace Mcustiel\MicrofrontendsComposer\Collections;

abstract class AbstractMap extends AbstractArrayCollection
{
    protected function hasKey($key): bool
    {
        return isset($this->data[$key]);
    }

    protected function setValue($key, $value): void
    {
        $this->data[$key] = $value;
    }

    protected function getByKey($key)
    {
        return $this->data[$key];
    }
}
