<?php

namespace Aternos\IO\System\File;

use Aternos\IO\Exception\DeleteException;
use Aternos\IO\Exception\IOException;
use Aternos\IO\Exception\ReadException;
use Aternos\IO\Exception\SeekException;
use Aternos\IO\Interfaces\Features\WriteInterface;

class TemporaryFile extends File
{
    /**
     * @param string $prefix
     * @param bool $deleteOnDestruct
     */
    public function __construct(string $prefix = "io-", protected bool $deleteOnDestruct = true)
    {
        $path = tempnam(sys_get_temp_dir(), $prefix);
        parent::__construct($path);
    }

    /**
     * @throws IOException
     * @throws ReadException
     * @throws SeekException
     */
    public function copyTo(WriteInterface $target): static
    {
        $this->setPosition(0);
        do {
            $target->write($this->read(5 * 1024 * 1024));
        } while (!$this->isEndOfFile());
        return $this;
    }

    /**
     * @throws DeleteException
     */
    public function __destruct()
    {
        if ($this->deleteOnDestruct) {
            $this->delete();
        }
    }

    /**
     * @return array|string[]
     */
    public function __serialize(): array
    {
        return [
            ...parent::__serialize(),
            "deleteOnDestruct" => $this->deleteOnDestruct
        ];
    }
}