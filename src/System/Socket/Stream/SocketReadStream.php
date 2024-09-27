<?php

namespace Aternos\IO\System\Socket\Stream;

use Aternos\IO\Interfaces\Types\Stream\ReadStreamInterface;
use Aternos\IO\System\Socket\Traits\ReadSocketTrait;

/**
 * Class SocketReadStream
 *
 * Stream element for read only sockets
 *
 * @package Aternos\IO\System\Socket\Stream
 */
class SocketReadStream extends SocketStreamElement implements ReadStreamInterface
{
    use ReadSocketTrait;

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return "socket read stream";
    }
}