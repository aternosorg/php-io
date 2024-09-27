<?php

namespace Aternos\IO\System\Socket\Stream;

use Aternos\IO\Interfaces\Types\Stream\WriteStreamInterface;
use Aternos\IO\System\Socket\Traits\WriteSocketTrait;

class SocketWriteStream extends SocketStreamElement implements WriteStreamInterface
{
    use WriteSocketTrait;

    public function getName(): string
    {
        return "socket write stream";
    }
}