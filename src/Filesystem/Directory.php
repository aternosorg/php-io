<?php

namespace Aternos\IO\Filesystem;

use Aternos\IO\Exception\CreateDirectoryException;
use Aternos\IO\Exception\DeleteException;
use Aternos\IO\Exception\MissingPermissionsException;
use Aternos\IO\Interfaces\Features\GetChildrenInterface;
use Aternos\IO\Interfaces\Types\DirectoryInterface;
use DirectoryIterator;
use Generator;

class Directory extends FilesystemElement implements DirectoryInterface
{
    /**
     * @throws MissingPermissionsException
     */
    public function getChildren(): Generator
    {
        if (!is_readable($this->path)) {
            throw new MissingPermissionsException("Could not read directory due to missing read permissions (" . $this->path . ")", $this);
        }

        if (!is_dir($this->path)) {
            return;
        }

        foreach (new DirectoryIterator($this->path) as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }

            yield static::getIOElementFromPath($fileInfo->getPathname());
        }
    }

    /**
     * @return Generator
     * @throws MissingPermissionsException
     */
    public function getChildrenRecursive(): Generator
    {
        foreach ($this->getChildren() as $child) {
            yield $child;
            if ($child instanceof GetChildrenInterface) {
                foreach ($child->getChildrenRecursive() as $subChild) {
                    yield $subChild;
                }
            }
        }
    }

    /**
     * @throws CreateDirectoryException
     */
    public function create(): static
    {
        if (!@mkdir($this->path, recursive: true)) {
            $error = error_get_last();
            throw new CreateDirectoryException("Could not create directory (" . $this->path . ")" . ($error ? ": " . $error["message"] : ""), $this);
        }
        return $this;
    }

    /**
     * @throws DeleteException
     * @throws MissingPermissionsException
     */
    public function delete(): static
    {
        if (!$this->exists()) {
            return $this;
        }
        foreach ($this->getChildren() as $child) {
            $child->delete();
        }
        if (!@rmdir($this->path)) {
            $error = error_get_last();
            throw new DeleteException("Could not delete directory (" . $this->path . ")" . ($error ? ": " . $error["message"] : ""), $this);
        }
        return $this;
    }
}