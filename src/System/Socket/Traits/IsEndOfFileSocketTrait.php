<?php

namespace Aternos\IO\System\Socket\Traits;

use Aternos\IO\Exception\IOException;

trait IsEndOfFileSocketTrait
{
    use SocketTrait;

    /**
     * @return bool
     * @throws IOException
     */
    public function isEndOfFile(): bool
    {
        return feof($this->getSocketResource());
    }
}