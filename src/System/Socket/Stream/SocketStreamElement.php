<?php

namespace Aternos\IO\System\Socket\Stream;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Interfaces\Features\GetNameInterface;
use Aternos\IO\System\Socket\Traits\CloseSocketTrait;
use Aternos\IO\System\Socket\Traits\GetSocketPositionTrait;
use Aternos\IO\System\Socket\Traits\GetSocketTrait;

/**
 * Class SocketStreamElement
 *
 * Base class for socket stream elements
 *
 * @package Aternos\IO\System\Socket\Stream
 */
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
     * @inheritDoc
     * @throws IOException
     */
    protected function getTypeForErrors(): string
    {
        return $this->getName();
    }

    /**
     * @inheritDoc
     */
    protected function getErrorContext(): ?string
    {
        return null;
    }
}