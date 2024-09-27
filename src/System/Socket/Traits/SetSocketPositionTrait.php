<?php

namespace Aternos\IO\System\Socket\Traits;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Exception\SeekException;

/**
 * Trait SetSocketPositionTrait
 *
 * Trait for socket based elements implementing {@link SetPositionInterface}
 *
 * @package Aternos\IO\System\Socket\Traits
 */
trait SetSocketPositionTrait
{
    use SocketTrait;

    /**
     * @inheritDoc
     * @throws SeekException|IOException
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