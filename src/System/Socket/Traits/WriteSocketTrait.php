<?php

namespace Aternos\IO\System\Socket\Traits;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Exception\MissingPermissionsException;
use Aternos\IO\Exception\WriteException;

trait WriteSocketTrait
{
    use SocketTrait;



    /**
     * @throws IOException
     * @throws WriteException
     */
    public function write(string $buffer): static
    {
        if (@fwrite($this->getSocketResource(), $buffer) === false) {
            $this->throwException("Could not write to {type}", WriteException::class);
        }
        return $this;
    }

}