<?php

namespace Aternos\IO\Test\Unit\System\Stream;

use Aternos\IO\Interfaces\Types\Stream\ReadStreamInterface;
use Aternos\IO\System\Socket\Stream\SocketReadStream;
use Aternos\IO\Test\Unit\System\Stream\Trait\SocketReadTestTrait;

class SocketReadStreamTest extends SocketStreamTestCase
{
    use SocketReadTestTrait;

    protected function createStream(): ReadStreamInterface
    {
        return new SocketReadStream($this->getLocalSocket());
    }

    public function testGetName(): void
    {
        $stream = $this->createStream();
        $this->assertEquals("socket read stream", $stream->getName());
    }
}