<?php

namespace Aternos\IO\System\Socket\Traits;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Exception\ReadException;

/**
 * Trait ReadSocketTrait
 *
 * Trait for socket based elements implementing {@link ReadInterface}
 *
 * @package Aternos\IO\System\Socket\Traits
 */
trait ReadSocketTrait
{
    use SocketTrait;

    /**
     * @inheritDoc
     * @throws ReadException|IOException
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