<?php

namespace Aternos\IO\System\Socket\Traits;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Exception\RewindException;
use Aternos\IO\Exception\SeekException;

/**
 * Trait RewindSocketPositionTrait
 *
 * Trait for socket based elements implementing {@link RewindPositionInterface}
 *
 * @package Aternos\IO\System\Socket\Traits
 */
trait RewindSocketPositionTrait
{
    use SocketTrait;

    /**
     * @inheritDoc
     * @throws SeekException|IOException
     */
    public function rewindPosition(): static
    {
        $file = $this->getSocketResource();
        if (!@rewind($file)) {
            $this->throwException("Could not rewind {type} position", RewindException::class);
        }
        return $this;
    }
}