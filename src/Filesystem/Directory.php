<?php

namespace Aternos\IO\Filesystem;

use Aternos\IO\Exception\CreateDirectoryException;
use Aternos\IO\Exception\DeleteException;
use Aternos\IO\Exception\GetTargetException;
use Aternos\IO\Exception\MissingPermissionsException;
use Aternos\IO\Filesystem\Link\DirectoryLink;
use Aternos\IO\Filesystem\Link\FileLink;
use Aternos\IO\Interfaces\Features\GetChildrenInterface;
use Aternos\IO\Interfaces\Features\GetPathInterface;
use Aternos\IO\Interfaces\Features\GetTargetInterface;
use Aternos\IO\Interfaces\Features\GetTargetPathInterface;
use Aternos\IO\Interfaces\Types\DirectoryInterface;
use DirectoryIterator;
use Generator;

class Directory extends FilesystemElement implements DirectoryInterface
{
    /**
     * @param bool $allowOutsideLinks
     * @return Generator<FilesystemInterface>
     * @throws MissingPermissionsException
     * @throws GetTargetException
     */
    public function getChildren(bool $allowOutsideLinks = false): Generator
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

            $element = static::getIOElementFromPath($fileInfo->getPathname());
            if (!$allowOutsideLinks && !$this->isElementInDirectory($element)) {
                continue;
            }
            yield $element;
        }
    }

    /**
     * @param bool $allowOutsideLinks
     * @param bool $followLinks
     * @return Generator
     * @throws MissingPermissionsException
     * @throws GetTargetException
     */
    public function getChildrenRecursive(bool $allowOutsideLinks = false, bool $followLinks = true): Generator
    {
        foreach ($this->getChildren($allowOutsideLinks) as $child) {
            yield $child;
            if ($child instanceof GetTargetInterface && !$followLinks) {
                continue;
            }
            if ($child instanceof GetChildrenInterface) {
                foreach ($child->getChildrenRecursive(true, $followLinks) as $subChild) {
                    if (!$allowOutsideLinks && !$this->isElementInDirectory($subChild)) {
                        continue;
                    }
                    yield $subChild;
                }
            }
        }
    }

    /**
     * @param GetPathInterface $element
     * @return bool
     * @throws GetTargetException
     */
    protected function isElementInDirectory(GetPathInterface $element): bool
    {
        if ($element instanceof FileLink || $element instanceof DirectoryLink) {
            return $this->isElementInDirectory($element->getTarget());
        }
        if ($element instanceof GetTargetPathInterface) {
            return $this->isPathInDirectory($element->getTargetPath());
        }
        return $this->isPathInDirectory($element->getPath());
    }

    /**
     * @param string $path
     * @return bool
     */
    protected function isPathInDirectory(string $path): bool
    {
        if (!str_ends_with($path, "/")) {
            $path .= "/";
        }
        return str_starts_with($path, $this->getPath());
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
     * @throws GetTargetException
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