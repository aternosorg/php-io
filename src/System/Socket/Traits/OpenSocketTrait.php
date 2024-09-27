<?php

namespace Aternos\IO\System\Socket\Traits;

use Aternos\IO\Exception\IOException;

/**
 * Trait OpenSocketTrait
 *
 * Trait for socket based elements using {@link SocketTrait} that open the socket as needed
 *
 * @package Aternos\IO\System\Socket\Traits
 */
trait OpenSocketTrait
{
    use SocketTrait;

    /**
     * @inheritDoc
     * @throws IOException
     */
    protected function getSocketResource(): mixed
    {
        if (!$this->hasSocketResource()) {
            $this->socketResource = $this->openSocketResource();
        }
        return $this->socketResource;
    }

    /**
     * Open the socket resource
     *
     * Will only be called if needed
     *
     * @return resource
     */
    abstract protected function openSocketResource(): mixed;
}