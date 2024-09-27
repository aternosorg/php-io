<?php

namespace Aternos\IO\System\Socket\Traits;

use Aternos\IO\Exception\IOException;

trait OpenSocketTrait
{
    use SocketTrait;

    /**
     * @return resource
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
     * @return resource
     */
    abstract protected function openSocketResource(): mixed;
}