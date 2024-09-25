<?php

namespace Aternos\IO\System\Socket\Traits;

use Aternos\IO\Exception\IOException;

trait CloseSocketTrait
{
    use SocketTrait;

    /**
     * @return $this
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