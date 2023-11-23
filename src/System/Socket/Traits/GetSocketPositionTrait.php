<?php

namespace Aternos\IO\System\Socket\Traits;

trait GetSocketPositionTrait
{
    use SocketTrait;

    public function getPosition(): int
    {
        $file = $this->getSocketResource();
        // According to the documentation, this can return false, but I don't know how.
        return @ftell($file);
    }
}