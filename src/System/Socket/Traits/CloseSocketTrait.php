<?php

namespace Aternos\IO\System\Socket\Traits;

trait CloseSocketTrait
{
    use SocketTrait;

    public function close(): static
    {
        if ($this->hasSocketResource()) {
            @fclose($this->getSocketResource());
            $this->clearSocketResource();
        }
        return $this;
    }
}