<?php

namespace Aternos\IO\System\Socket\Stream;

use Aternos\IO\Interfaces\Types\Stream\ReadStreamInterface;
use Aternos\IO\System\Socket\Traits\ReadSocketTrait;

class SocketReadStream extends SocketStreamElement implements ReadStreamInterface
{
    use ReadSocketTrait;

    public function getName(): string
    {
        return "socket read stream";
    }
}