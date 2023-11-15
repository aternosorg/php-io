<?php

namespace Aternos\IO\Filesystem;

use Aternos\IO\Exception\CreateFileException;
use Aternos\IO\Exception\IOException;
use Aternos\IO\Exception\MissingPermissionsException;
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
            throw new MissingPermissionsException("Could not open file due to missing read permissions: " . $this->path, $this);
        }
    }

    public function close(): static
    {
        // TODO: Implement close() method.
    }

    public function getPosition(): int
    {
        // TODO: Implement getPosition() method.
    }

    public function getSize(): int
    {
        // TODO: Implement getSize() method.
    }

    public function getName(): string
    {
        return basename($this->path);
    }

    public function read(int $length): string
    {
        // TODO: Implement read() method.
    }

    public function setPosition(int $position): static
    {
        // TODO: Implement setPosition() method.
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
}