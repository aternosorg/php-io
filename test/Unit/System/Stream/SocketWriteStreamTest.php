<?php

namespace Aternos\IO\Test\Unit\System\Stream;

use Aternos\IO\Exception\IOException;
use Aternos\IO\System\Socket\Stream\SocketWriteStream;
use Aternos\IO\Test\Unit\System\Stream\Trait\SocketWriteTestTrait;

class SocketWriteStreamTest extends SocketStreamTestCase
{
    use SocketWriteTestTrait;

    protected function createStream(): SocketWriteStream
    {
        return new SocketWriteStream($this->getLocalSocket());
    }

    /**
     * @throws IOException
     */
    public function testGetName(): void
    {
        $stream = $this->createStream();
        $this->assertEquals("socket write stream", $stream->getName());
    }
}