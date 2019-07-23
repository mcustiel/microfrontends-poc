<?php

declare(strict_types=1);

namespace Mcustiel\MicrofrontendsComposer\Collections;

use Mcustiel\MicrofrontendsComposer\ServiceExecutionData;

class ServiceDataCollection extends AbstractMap
{
    /** @var int */
    private $count = 0;

    public function add(ServiceExecutionData $data): void
    {
        parent::setValue($this->count++, $data);
    }

    public function replace(int $index, ServiceExecutionData $data): void
    {
        if (!parent::hasKey($index)) {
            throw new \OutOfBoundsException('Out of bounds');
        }
        parent::setValue($index, $data);
    }

    public function has(int $index): bool
    {
        return parent::hasKey($index);
    }

    public function get(int $index): ServiceExecutionData
    {
        return parent::getByKey($index);
    }
}
