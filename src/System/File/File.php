<?php

namespace Aternos\IO\System\File;

use Aternos\IO\Exception\CreateDirectoryException;
use Aternos\IO\Exception\CreateFileException;
use Aternos\IO\Exception\DeleteException;
use Aternos\IO\Exception\IOException;
use Aternos\IO\Exception\MissingPermissionsException;
use Aternos\IO\Exception\ReadException;
use Aternos\IO\Exception\StatException;
use Aternos\IO\Exception\WriteException;
use Aternos\IO\System\Directory\Directory;
use Aternos\IO\System\FilesystemElement;
use Aternos\IO\Interfaces\Types\FileInterface;
use Aternos\IO\System\Socket\Traits\CloseSocketTrait;
use Aternos\IO\System\Socket\Traits\GetSocketPositionTrait;
use Aternos\IO\System\Socket\Traits\IsEndOfFileSocketTrait;
use Aternos\IO\System\Socket\Traits\ReadSocketTrait;
use Aternos\IO\System\Socket\Traits\SetSocketPositionTrait;
use Aternos\IO\System\Socket\Traits\TruncateSocketTrait;
use Aternos\IO\System\Socket\Traits\WriteSocketTrait;

/**
 * Class File
 *
 * Represents a file object.
 */
class File extends FilesystemElement implements FileInterface
{
    use CloseSocketTrait,
        GetSocketPositionTrait,
        IsEndOfFileSocketTrait,
        ReadSocketTrait,
        SetSocketPositionTrait,
        TruncateSocketTrait,
        WriteSocketTrait {
        write as traitWrite;
        read as traitRead;
    }

    /**
     * @throws IOException
     */
    protected function openSocketResource(): mixed
    {
        $resource = @fopen($this->path, $this->getMode());

        if (!$resource) {
            $this->throwException("Could not open {type}");
        }

        return $resource;
    }

    /**
     * @return string
     * @throws MissingPermissionsException|CreateDirectoryException|IOException
     */
    protected function getMode(): string
    {
        if (!file_exists($this->path)) {
            $parentDirectory = new Directory(dirname($this->path));
            if (!$parentDirectory->exists()) {
                $parentDirectory->create();
            }

            if (is_writable($parentDirectory->getPath())) {
                return "c+b";
            }
            $this->throwException("Could not open {type} due to missing write permissions in parent directory", MissingPermissionsException::class);
        }
        if (is_readable($this->path) && is_writable($this->path)) {
            return "c+b";
        }
        if (is_readable($this->path)) {
            return "rb";
        }
        if (is_writable($this->path)) {
            return "wb";
        }
        $this->throwException("Could not open {type} due to missing read and write permissions", MissingPermissionsException::class);
    }

    /**
     * Retrieves the size of the file or directory.
     *
     * @return int The size of the file or directory in bytes.
     * @throws StatException If the size cannot be determined.
     * @throws IOException
     */
    public function getSize(): int
    {
        $size = @filesize($this->path);
        if ($size === false) {
            $this->throwException("Could not get {type} size", StatException::class);
        }
        return $size;
    }

    public function getName(): string
    {
        return basename($this->path);
    }

    /**
     * @throws IOException
     * @throws ReadException
     */
    public function read(int $length): string
    {
        try {
            return $this->traitRead($length);
        } catch (ReadException $exception) {
            if (!is_readable($this->path)) {
                $this->throwException("Could not read from {type} due to missing read permissions", MissingPermissionsException::class);
            }
            throw $exception;
        }
    }


    /**
     * @throws IOException
     * @throws WriteException
     */
    public function write(string $buffer): static
    {
        try {
            $this->traitWrite($buffer);
        } catch (WriteException $exception) {
            if (!is_writable($this->path)) {
                $this->throwException("Could not write to {type} due to missing write permissions", MissingPermissionsException::class);
            }
            throw $exception;
        }
        return $this;
    }

    /**
     * @throws CreateFileException|CreateDirectoryException|IOException
     */
    public function create(): static
    {
        $parentDirectory = new Directory(dirname($this->path));
        if (!$parentDirectory->exists()) {
            $parentDirectory->create();
        }

        if (!@touch($this->path)) {
            $this->throwException("Could not create {type}", CreateFileException::class);
        }
        return $this;
    }

    /**
     * @throws DeleteException|IOException
     */
    public function delete(): static
    {
        if (!$this->exists()) {
            return $this;
        }
        if (!@unlink($this->path)) {
            $this->throwException("Could not delete {type}", DeleteException::class);
        }
        return $this;
    }


    /**
     * @return string
     */
    protected function getTypeForErrors(): string
    {
        return "file";
    }

    /**
     * @return string|null
     */
    protected function getErrorContext(): ?string
    {
        return $this->path;
    }
}