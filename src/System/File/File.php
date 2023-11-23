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
            $error = error_get_last();
            throw new IOException("Could not open file (" . $this->path . ")" . ($error ? ": " . $error["message"] : ""), $this);
        }

        return $resource;
    }

    /**
     * @return string
     * @throws MissingPermissionsException|CreateDirectoryException
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
            throw new MissingPermissionsException("Could not open file due to missing write permissions in parent directory (" . $this->path . ")", $this);
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
        throw new MissingPermissionsException("Could not open file due to missing permissions (" . $this->path . ")", $this);
    }

    /**
     * @throws StatException
     * @throws IOException
     */
    public function getSize(): int
    {
        $size = @filesize($this->path);
        if (!$size) {
            $error = error_get_last();
            throw new StatException("Could not get file size (" . $this->path . ")" . ($error ? ": " . $error["message"] : ""), $this);
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
     * @throws CreateFileException|CreateDirectoryException
     */
    public function create(): static
    {
        $parentDirectory = new Directory(dirname($this->path));
        if (!$parentDirectory->exists()) {
            $parentDirectory->create();
        }

        if (!@touch($this->path)) {
            $error = error_get_last();
            throw new CreateFileException("Could not create file (" . $this->path . ")" . ($error ? ": " . $error["message"] : ""), $this);
        }
        return $this;
    }

    /**
     * @throws DeleteException
     */
    public function delete(): static
    {
        if (!$this->exists()) {
            return $this;
        }
        if (!@unlink($this->path)) {
            $error = error_get_last();
            throw new DeleteException("Could not delete file (" . $this->path . ")" . ($error ? ": " . $error["message"] : ""), $this);
        }
        return $this;
    }

    public function __destruct()
    {
        $this->close();
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