<?php

namespace Aternos\IO\System\Socket\Traits;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Exception\StatException;

/**
 * Trait GetSizeTrait
 *
 * Trait for socket based elements implementing {@link GetSizeInterface}
 *
 * @package Aternos\IO\System\Socket\Traits
 */
trait GetSizeTrait
{
    use SocketTrait;

    /**
     * @inheritDoc
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