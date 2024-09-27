<?php

namespace Aternos\IO\System\Socket\Stream;

use Aternos\IO\Interfaces\Types\Stream\StreamInterface;
use Aternos\IO\System\Socket\Traits\ReadSocketTrait;
use Aternos\IO\System\Socket\Traits\WriteSocketTrait;

class SocketStream extends SocketStreamElement implements StreamInterface
{
    use WriteSocketTrait, ReadSocketTrait;

    public function getName(): string
    {
        return "socket stream";
    }
}