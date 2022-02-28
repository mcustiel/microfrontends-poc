<?php

declare(strict_types=1);

namespace Mcustiel\MicrofrontendsComposer;

use InvalidArgumentException;

class ServiceId
{
    /** @var string */
    private $id;

    public function __construct(string $id)
    {
        $this->ensureAlnum($id);
        $this->id = $id;
    }

    public function asString(): string
    {
        return $this->id;
    }

    private function ensureAlnum(string $id): void
    {
        if (!ctype_alnum($id)) {
            throw new InvalidArgumentException(sprintf('Expected alphanumeric string, got %s', $id));
        }
    }
}
