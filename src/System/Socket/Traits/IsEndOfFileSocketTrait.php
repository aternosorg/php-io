<?php

namespace Aternos\IO\System\Socket\Traits;

use Aternos\IO\Exception\IOException;

/**
 * Trait IsEndOfFileSocketTrait
 *
 * Trait for socket based elements implementing {@link IsEndOfFileInterface}
 *
 * @package Aternos\IO\System\Socket\Traits
 */
trait IsEndOfFileSocketTrait
{
    use SocketTrait;

    /**
     * @inheritDoc
     * @throws IOException
     */
    public function isEndOfFile(): bool
    {
        return feof($this->getSocketResource());
    }
}