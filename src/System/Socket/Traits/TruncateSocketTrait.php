<?php

namespace Aternos\IO\System\Socket\Traits;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Exception\TruncateException;

trait TruncateSocketTrait
{
    use SocketTrait;

    /**
     * @throws IOException
     * @throws TruncateException
     */
    public function truncate(int $size = 0): static
    {
        if (!@ftruncate($this->getSocketResource(), $size)) {
            $this->throwException("Could not truncate {type}", TruncateException::class);
        }
        return $this;
    }
}