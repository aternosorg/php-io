<?php

namespace Aternos\IO\Filesystem;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Exception\MissingPermissionsException;
use Aternos\IO\Exception\TruncateException;
use Aternos\IO\Exception\WriteException;
use Aternos\IO\Interfaces\Types\File\ReadWriteFileInterface;

class ReadWriteFile extends ReadFile implements ReadWriteFileInterface
{
    protected string $mode = "c+b";

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
            $error = error_get_last();
            throw new WriteException("Could not write to file (" . $this->path . ")" . ($error ? ": " . $error["message"] : ""), $this);
        }
        return $this;
    }

    /**
     * @return void
     * @throws MissingPermissionsException
     */
    protected function checkPermissions(): void
    {
        if (!file_exists($this->path)) {
            return;
        }

        parent::checkPermissions();

        if (!is_writable($this->path)) {
            throw new MissingPermissionsException("Could not open file due to missing write permissions (" . $this->path . ")", $this);
        }
    }
}