<?php

namespace Aternos\IO\Test\Unit\System\Stream\Trait;

use Aternos\IO\Exception\ReadException;
use Aternos\IO\Interfaces\Types\Stream\ReadStreamInterface;
use ReflectionException;
use ReflectionObject;

trait SocketReadTestTrait
{
    abstract protected function createStream(): ReadStreamInterface;

    public function testRead(): void
    {
        fwrite($this->getRemoteSocket(), "test");

        $stream = $this->createStream();
        $this->assertEquals("test", $stream->read(4));
    }

    public function testReadEmpty(): void
    {
        $stream = $this->createStream();
        $this->assertEquals("", $stream->read(4));
    }

    public function testReadNothing(): void
    {
        fwrite($this->getRemoteSocket(), "test");

        $stream = $this->createStream();
        $this->assertEquals("", $stream->read(0));
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testThrowsExceptionOnImpossibleRead(): void
    {
        $stream = $this->createStream();

        $reflectionObject = new ReflectionObject($stream);
        $reflectionObject->getProperty("socketResource")->setValue($stream, fopen("/dev/null", "w"));

        $this->expectException(ReadException::class);
        $this->expectExceptionMessage("Could not read from " . $stream->getName() . ": fread(): Read of 8192 bytes failed with errno=9 Bad file descriptor");
        $stream->read(4);
    }
}