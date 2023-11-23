<?php

namespace Aternos\IO\System\Socket\Traits;

trait IsEndOfFileSocketTrait
{
    use SocketTrait;

    public function isEndOfFile(): bool
    {
        return feof($this->getSocketResource());
    }
}