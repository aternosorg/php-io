<?php

namespace Aternos\IO\Filesystem;

use Aternos\IO\Exception\CreateFileException;
use Aternos\IO\Exception\DeleteException;
use Aternos\IO\Exception\ReadException;
use Aternos\IO\Exception\IOException;
use Aternos\IO\Exception\MissingPermissionsException;
use Aternos\IO\Exception\SeekException;
use Aternos\IO\Exception\StatException;
use Aternos\IO\Interfaces\Types\File\ReadFileInterface;

class ReadFile extends FilesystemElement implements ReadFileInterface
{
    /**
     * @var resource
     */
    protected mixed $fileResource = null;
    protected string $mode = "rb";

    /**
     * @throws IOException
     */
    protected function getFileResource(): mixed
    {
        if ($this->fileResource) {
            return $this->fileResource;
        }

        $this->checkPermissions();

        $this->fileResource = @fopen($this->path, $this->mode);

        if (!$this->fileResource) {
            $error = error_get_last();
            throw new IOException("Could not open file (" . $this->path . ")" . ($error ? ": " . $error["message"] : ""), $this);
        }

        return $this->fileResource;
    }

    /**
     * @throws MissingPermissionsException
     */
    protected function checkPermissions(): void
    {
        if (!file_exists($this->path)) {
            return;
        }

        if (!is_readable($this->path)) {
            throw new MissingPermissionsException("Could not open file due to missing read permissions (" . $this->path . ")", $this);
        }
    }

    public function close(): static
    {
        if ($this->fileResource) {
            @fclose($this->fileResource);
            $this->fileResource = null;
        }
        return $this;
    }

    /**
     * @throws IOException
     */
    public function getPosition(): int
    {
        $file = $this->getFileResource();
        // According to the documentation, this can return false, but I don't know how.
        return @ftell($file);
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
        if ($length === 0) {
            return "";
        }
        $file = $this->getFileResource();
        $buffer = @fread($file, $length);
        if ($buffer === false) {
            $error = error_get_last();
            throw new ReadException("Could not read file (" . $this->path . ")" . ($error ? ": " . $error["message"] : ""), $this);
        }
        return $buffer;
    }

    /**
     * @throws IOException
     * @throws SeekException
     */
    public function setPosition(int $position): static
    {
        $file = $this->getFileResource();
        if (@fseek($file, $position) !== 0) {
            $error = error_get_last();
            throw new SeekException("Could not set file position (" . $this->path . ")" . ($error ? ": " . $error["message"] : ""), $this);
        }
        return $this;
    }

    /**
     * @throws CreateFileException
     */
    public function create(): static
    {
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
}