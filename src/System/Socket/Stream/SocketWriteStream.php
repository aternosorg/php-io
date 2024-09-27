<?php

namespace Aternos\IO\System\Socket\Stream;

use Aternos\IO\Interfaces\Types\Stream\WriteStreamInterface;
use Aternos\IO\System\Socket\Traits\WriteSocketTrait;

/**
 * Class SocketWriteStream
 *
 * Stream element for write only sockets
 *
 * @package Aternos\IO\System\Socket\Stream
 */
class SocketWriteStream extends SocketStreamElement implements WriteStreamInterface
{
    use WriteSocketTrait;

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return "socket write stream";
    }
}