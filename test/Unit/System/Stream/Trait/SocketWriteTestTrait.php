<?php

namespace Aternos\IO\Test\Unit\System\Stream\Trait;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Exception\WriteException;
use Aternos\IO\Interfaces\Types\Stream\WriteStreamInterface;
use ReflectionException;
use ReflectionObject;

trait SocketWriteTestTrait
{
    abstract protected function createStream(): WriteStreamInterface;

    /**
     * @return void
     * @throws IOException
     * @throws WriteException
     */
    public function testWrite(): void
    {
        $stream = $this->createStream();
        $stream->write("test");
        $this->assertEquals("test", fread($this->getRemoteSocket(), 4));
    }

    /**
     * @return void
     * @throws IOException
     * @throws WriteException
     */
    public function testWriteEmpty(): void
    {
        $stream = $this->createStream();
        $stream->write("");
        $this->assertEquals("", fread($this->getRemoteSocket(), 4));
    }

    /**
     * @return void
     * @throws IOException
     * @throws WriteException
     * @throws ReflectionException
     */
    public function testThrowsExceptionOnImpossibleWrite(): void
    {
        $stream = $this->createStream();

        $reflectionObject = new ReflectionObject($stream);
        $reflectionObject->getProperty("socketResource")->setValue($stream, fopen("/dev/null", "r"));

        $this->expectException(WriteException::class);
        $this->expectExceptionMessage("Could not write to " . $stream->getName() . ": fwrite(): Write of 4 bytes failed with errno=9 Bad file descriptor");
        $stream->write("test");
    }
}