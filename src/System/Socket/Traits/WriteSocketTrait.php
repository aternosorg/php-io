<?php

namespace Aternos\IO\System\Socket\Traits;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Exception\WriteException;

/**
 * Trait WriteSocketTrait
 *
 * Trait for socket based elements implementing {@link WriteInterface}
 *
 * @package Aternos\IO\System\Socket\Traits
 */
trait WriteSocketTrait
{
    use SocketTrait;

    /**
     * @inheritDoc
     * @throws IOException
     * @throws WriteException
     */
    public function write(string $buffer): static
    {
        if (@fwrite($this->getSocketResource(), $buffer) === false) {
            $this->throwException("Could not write to {type}", WriteException::class);
        }
        return $this;
    }
}