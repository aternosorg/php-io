<?php

namespace Aternos\IO\System\Socket\Traits;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Exception\TellException;

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

        $result = @ftell($file);
        if ($result === false) {
            $this->throwException("Could not get {type} position", TellException::class);
        }
        return @ftell($file);
    }
}