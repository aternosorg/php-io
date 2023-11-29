<?php

namespace Aternos\IO\System\Socket\Traits;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Exception\StatException;

trait GetSizeTrait
{
    use SocketTrait;

    /**
     * @return int
     * @throws IOException
     */
    public function getSize(): int
    {
        $stat = fstat($this->getSocketResource());
        if ($stat === false) {
            $this->throwException("Could not get {type} size", StatException::class);
        }
        return $stat["size"];
    }
}