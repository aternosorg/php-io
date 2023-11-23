<?php

namespace Aternos\IO\System\Socket\Traits;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Exception\SeekException;

trait SetSocketPositionTrait
{
    use SocketTrait;

    /**
     * @throws IOException
     * @throws SeekException
     */
    public function setPosition(int $position): static
    {
        $file = $this->getSocketResource();
        if (@fseek($file, $position) !== 0) {
            $this->throwException("Could not set {type} position", SeekException::class);
        }
        return $this;
    }
}