<?php

namespace Aternos\IO\System\Socket\Traits;

use Aternos\IO\Exception\IOException;

/**
 * Trait CloseSocketTrait
 *
 * Trait for socket based elements implementing {@link CloseInterface}
 *
 * @package Aternos\IO\System\Socket\Traits
 */
trait CloseSocketTrait
{
    use SocketTrait;

    /**
     * @inheritDoc
     * @throws IOException
     */
    public function close(): static
    {
        if ($this->hasSocketResource()) {
            @fclose($this->getSocketResource());
            $this->clearSocketResource();
        }
        return $this;
    }

    /**
     * @throws IOException
     */
    public function __destruct()
    {
        $this->close();
    }
}