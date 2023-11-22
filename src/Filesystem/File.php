<?php

namespace Aternos\IO\Filesystem;

use Aternos\IO\Exception\CreateDirectoryException;
use Aternos\IO\Exception\CreateFileException;
use Aternos\IO\Exception\DeleteException;
use Aternos\IO\Exception\IOException;
use Aternos\IO\Exception\MissingPermissionsException;
use Aternos\IO\Exception\ReadException;
use Aternos\IO\Exception\SeekException;
use Aternos\IO\Exception\StatException;
use Aternos\IO\Exception\TruncateException;
use Aternos\IO\Exception\WriteException;
use Aternos\IO\Interfaces\Types\FileInterface;

class File extends FilesystemElement implements FileInterface
{
    /**
     * @var resource
     */
    protected mixed $fileResource = null;

    /**
     * @throws IOException
     */
    protected function getFileResource(): mixed
    {
        if ($this->fileResource) {
            return $this->fileResource;
        }

        $this->fileResource = @fopen($this->path, $this->getMode());

        if (!$this->fileResource) {
            $error = error_get_last();
            throw new IOException("Could not open file (" . $this->path . ")" . ($error ? ": " . $error["message"] : ""), $this);
        }

        return $this->fileResource;
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
            if (!is_readable($this->path)) {
                throw new MissingPermissionsException("Could not read file due to missing read permissions (" . $this->path . ")", $this);
            }

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


    /**
     * @throws IOException
     * @throws TruncateException
     */
    public function truncate(int $size = 0): static
    {
        if (!@ftruncate($this->getFileResource(), $size)) {
            $error = error_get_last();
            throw new TruncateException("Could not truncate file (" . $this->path . ")" . ($error ? ": " . $error["message"] : ""), $this);
        }
        return $this;
    }

    /**
     * @throws IOException
     * @throws WriteException
     */
    public function write(string $buffer): static
    {
        if (@fwrite($this->getFileResource(), $buffer) === false) {
            if (!is_writable($this->path)) {
                throw new MissingPermissionsException("Could not write to file due to missing write permissions (" . $this->path . ")", $this);
            }

            $error = error_get_last();
            throw new WriteException("Could not write to file (" . $this->path . ")" . ($error ? ": " . $error["message"] : ""), $this);
        }
        return $this;
    }

    public function __destruct()
    {
        $this->close();
    }
}