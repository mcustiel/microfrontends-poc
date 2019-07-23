<?php

namespace Mcustiel\MicrofrontendsComposer;

use RuntimeException;
use Zend\Diactoros\Stream;

class StringStream extends Stream
{
    public function __construct(string $data)
    {
        parent::__construct($this->createAndWriteMemoryStream($data));
    }

    /** @return resource */
    private function createAndWriteMemoryStream(string $data)
    {
        $stream = fopen('php://memory', 'r+');
        if ($stream === false) {
            throw new RuntimeException('Could not open memory stream');
        }
        fwrite($stream, $data);
        rewind($stream);

        return $stream;
    }
}
