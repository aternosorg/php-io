<?php

namespace Aternos\IO\System\Socket\Traits;

/**
 * Trait GetSocketTrait
 *
 * Trait for socket based elements using {@link SocketTrait} that set the socket resource themselves
 * without opening it using the {@link OpenSocketTrait}
 *
 * @package Aternos\IO\System\Socket\Traits
 */
trait GetSocketTrait
{
    use SocketTrait;

    /**
     * @inheritDoc
     */
    protected function getSocketResource(): mixed
    {
        return $this->socketResource;
    }
}