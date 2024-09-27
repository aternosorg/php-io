<?php

namespace Aternos\IO\System\Socket\Stream;

use Aternos\IO\Interfaces\Types\Stream\StreamInterface;
use Aternos\IO\System\Socket\Traits\ReadSocketTrait;
use Aternos\IO\System\Socket\Traits\WriteSocketTrait;

/**
 * Class SocketStream
 *
 * Stream element for sockets
 *
 * @package Aternos\IO\System\Socket\Stream
 */
class SocketStream extends SocketStreamElement implements StreamInterface
{
    use WriteSocketTrait, ReadSocketTrait;

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return "socket stream";
    }
}