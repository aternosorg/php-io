<?php

namespace Aternos\IO\System\Socket\Traits;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Exception\ReadException;

trait ReadSocketTrait
{
    use SocketTrait;

    /**
     * @throws IOException
     * @throws ReadException
     */
    public function read(int $length): string
    {
        if ($length === 0) {
            return "";
        }
        $buffer = @fread($this->getSocketResource(), $length);
        if ($buffer === false) {
            $this->throwException("Could not read from {type}", ReadException::class);
        }
        return $buffer;
    }
}