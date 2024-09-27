<?php

namespace Aternos\IO\System\Socket\Traits;

trait GetSocketTrait
{
    use SocketTrait;

    /**
     * @return resource
     */
    protected function getSocketResource(): mixed
    {
        return $this->socketResource;
    }
}