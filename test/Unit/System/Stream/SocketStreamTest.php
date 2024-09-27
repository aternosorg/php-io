<?php

namespace Aternos\IO\Test\Unit\System\Stream;

use Aternos\IO\Interfaces\Types\Stream\StreamInterface;
use Aternos\IO\System\Socket\Stream\SocketStream;
use Aternos\IO\Test\Unit\System\Stream\Trait\SocketReadTestTrait;
use Aternos\IO\Test\Unit\System\Stream\Trait\SocketWriteTestTrait;

class SocketStreamTest extends SocketStreamTestCase
{
    use SocketReadTestTrait, SocketWriteTestTrait;

    protected function createStream(): StreamInterface
    {
        return new SocketStream($this->getLocalSocket());
    }

    public function testGetName(): void
    {
        $stream = $this->createStream();
        $this->assertEquals("socket stream", $stream->getName());
    }
}