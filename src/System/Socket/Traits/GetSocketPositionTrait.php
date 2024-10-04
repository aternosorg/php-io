<?php

namespace Aternos\IO\System\Socket\Traits;

use Aternos\IO\Exception\IOException;

/**
 * Trait GetSocketPositionTrait
 *
 * Trait for socket based elements implementing {@link GetPositionInterface}
 *
 * @package Aternos\IO\System\Socket\Traits
 */
trait GetSocketPositionTrait
{
    use SocketTrait;

    /**
     * @inheritDoc
     * @throws IOException
     */
    public function getPosition(): int
    {
        $file = $this->getSocketResource();
        return @ftell($file);
    }
}