<?php

namespace Aternos\IO\Test\Unit\System\Stream;

use Aternos\IO\Interfaces\Features\CloseInterface;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionObject;

abstract class SocketStreamTestCase extends TestCase
{
    /**
     * @var ?resource[]
     */
    protected ?array $sockets = null;

    protected function setUp(): void
    {
        $this->sockets = stream_socket_pair(STREAM_PF_UNIX, STREAM_SOCK_STREAM, STREAM_IPPROTO_IP);
        foreach ($this->sockets as $socket) {
            stream_set_blocking($socket, false);
        }
    }

    protected function getLocalSocket(): mixed
    {
        return $this->sockets[0];
    }

    protected function getRemoteSocket(): mixed
    {
        return $this->sockets[1];
    }

    abstract protected function createStream(): CloseInterface;

    /**
     * @throws ReflectionException
     */
    public function testClose(): void
    {
        $stream = $this->createStream();

        $reflectionObject = new ReflectionObject($stream);
        $file = $reflectionObject->getProperty("socketResource")->getValue($stream);
        $this->assertIsResource($file);

        $stream->close();

        $this->assertIsClosedResource($file);
        $null = $reflectionObject->getProperty("socketResource")->getValue($stream);
        $this->assertNull($null);
    }

    protected function tearDown(): void
    {
        if ($this->sockets) {
            foreach ($this->sockets as $socket) {
                if (!is_resource($socket)) {
                    continue;
                }
                fclose($socket);
            }
        }
    }
}