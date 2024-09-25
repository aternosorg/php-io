<?php

namespace Aternos\IO\System\Socket\Traits;

use Aternos\IO\Exception\IOException;

trait GetSocketPositionTrait
{
    use SocketTrait;

    /**
     * @return int
     * @throws IOException
     */
    public function getPosition(): int
    {
        $file = $this->getSocketResource();
        // According to the documentation, this can return false, but I don't know how.
        return @ftell($file);
    }
}