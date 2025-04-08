<?php

namespace Aternos\IO\System\File;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Interfaces\Types\VolatileFileInterface;
use Aternos\IO\System\Socket\Traits\CloseSocketTrait;
use Aternos\IO\System\Socket\Traits\GetSizeTrait;
use Aternos\IO\System\Socket\Traits\GetSocketPositionTrait;
use Aternos\IO\System\Socket\Traits\IsEndOfFileSocketTrait;
use Aternos\IO\System\Socket\Traits\OpenSocketTrait;
use Aternos\IO\System\Socket\Traits\ReadSocketTrait;
use Aternos\IO\System\Socket\Traits\RewindSocketPositionTrait;
use Aternos\IO\System\Socket\Traits\SetSocketPositionTrait;
use Aternos\IO\System\Socket\Traits\TruncateSocketTrait;
use Aternos\IO\System\Socket\Traits\WriteSocketTrait;

/**
 * Class TempMemoryFile
 *
 * Temporary file in memory
 *
 * @package Aternos\IO\System\File
 */
class TempMemoryFile implements VolatileFileInterface
{
    protected string $address = "php://memory";
    protected string $mode = "c+b";

    use OpenSocketTrait,
        CloseSocketTrait,
        GetSocketPositionTrait,
        IsEndOfFileSocketTrait,
        ReadSocketTrait,
        SetSocketPositionTrait,
        RewindSocketPositionTrait,
        TruncateSocketTrait,
        WriteSocketTrait,
        GetSizeTrait;

    /**
     * @inheritDoc
     */
    public function getName(): ?string
    {
        return "memory";
    }

    /**
     * @inheritDoc
     */
    protected function getTypeForErrors(): string
    {
        return $this->getName() . " file";
    }

    /**
     * @inheritDoc
     */
    protected function getErrorContext(): ?string
    {
        return null;
    }

    /**
     * @inheritDoc
     * @throws IOException
     * @noinspection PhpMixedReturnTypeCanBeReducedInspection
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