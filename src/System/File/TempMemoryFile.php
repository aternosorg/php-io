<?php

namespace Aternos\IO\System\File;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Interfaces\Types\VolatileFileInterface;
use Aternos\IO\System\Socket\Traits\CloseSocketTrait;
use Aternos\IO\System\Socket\Traits\GetSizeTrait;
use Aternos\IO\System\Socket\Traits\GetSocketPositionTrait;
use Aternos\IO\System\Socket\Traits\IsEndOfFileSocketTrait;
use Aternos\IO\System\Socket\Traits\ReadSocketTrait;
use Aternos\IO\System\Socket\Traits\SetSocketPositionTrait;
use Aternos\IO\System\Socket\Traits\TruncateSocketTrait;
use Aternos\IO\System\Socket\Traits\WriteSocketTrait;

class TempMemoryFile implements VolatileFileInterface
{
    protected string $address = "php://memory";
    protected string $mode = "c+b";

    use CloseSocketTrait,
        GetSocketPositionTrait,
        IsEndOfFileSocketTrait,
        ReadSocketTrait,
        SetSocketPositionTrait,
        TruncateSocketTrait,
        WriteSocketTrait,
        GetSizeTrait;

    public function getName(): ?string
    {
        return "memory";
    }

    protected function getTypeForErrors(): string
    {
        return $this->getName() . " file";
    }

    protected function getErrorContext(): ?string
    {
        return null;
    }

    /**
     * Opens a socket resource using the "php://memory" stream wrapper.
     *
     * @return resource A resource of type "stream" on success
     * @throws IOException if the resource could not be opened.
     */
    protected function openSocketResource(): mixed
    {
        $resource = @fopen($this->address, $this->mode);

        if (!$resource) {
            $this->throwException("Could not open {type}");
        }

        return $resource;
    }
}