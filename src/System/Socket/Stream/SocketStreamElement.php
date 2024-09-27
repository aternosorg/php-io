<?php

namespace Aternos\IO\System\Socket\Stream;

use Aternos\IO\Interfaces\Features\GetNameInterface;
use Aternos\IO\System\Socket\Traits\CloseSocketTrait;
use Aternos\IO\System\Socket\Traits\GetSocketPositionTrait;
use Aternos\IO\System\Socket\Traits\GetSocketTrait;

abstract class SocketStreamElement implements GetNameInterface
{
    use GetSocketTrait,
        CloseSocketTrait,
        GetSocketPositionTrait;

    /**
     * @param mixed $socket
     */
    public function __construct(mixed $socket)
    {
        $this->socketResource = $socket;
    }

    /**
     * @return string
     */
    protected function getTypeForErrors(): string
    {
        return $this->getName();
    }

    /**
     * @return string|null
     */
    protected function getErrorContext(): ?string
    {
        return null;
    }
}