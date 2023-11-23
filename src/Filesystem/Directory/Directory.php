<?php

namespace Aternos\IO\Filesystem\Directory;

use Aternos\IO\Exception\CreateDirectoryException;
use Aternos\IO\Exception\DeleteException;
use Aternos\IO\Exception\GetTargetException;
use Aternos\IO\Exception\MissingPermissionsException;
use Aternos\IO\Filesystem\FilesystemElement;
use Aternos\IO\Filesystem\Link\Link;
use Aternos\IO\Interfaces\Features\GetChildrenInterface;
use Aternos\IO\Interfaces\Features\GetPathInterface;
use Aternos\IO\Interfaces\Features\GetTargetInterface;
use Aternos\IO\Interfaces\Types\DirectoryInterface;
use DirectoryIterator;
use Generator;

class Directory extends FilesystemElement implements DirectoryInterface
{
    public const MAX_DEPTH = 100;

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
     * @param int $currentDepth
     * @return Generator
     * @throws GetTargetException
     * @throws MissingPermissionsException
     */
    public function getChildrenRecursive(bool $allowOutsideLinks = false, bool $followLinks = true, int $currentDepth = 0): Generator
    {
        $currentDepth++;
        foreach ($this->getChildren($allowOutsideLinks) as $child) {
            yield $child;
            if ($child instanceof GetTargetInterface && !$followLinks) {
                continue;
            }
            if ($child instanceof GetChildrenInterface) {
                if ($currentDepth >= static::MAX_DEPTH) {
                    continue;
                }
                foreach ($child->getChildrenRecursive(true, $followLinks, $currentDepth) as $subChild) {
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
        if ($element instanceof Link) {
            return $this->isLinkInDirectory($element);
        }
        return $this->isPathInDirectory($element->getPath());
    }

    /**
     * @throws GetTargetException
     */
    protected function isLinkInDirectory(Link $link): bool
    {
        $paths = [];
        $current = $link;
        do {
            if (in_array($current->getPath(), $paths)) {
                return false;
            }
            $paths[] = $current->getPath();

            if (!$this->isPathInDirectory($current->getPath())) {
                return false;
            }

            if (!$current instanceof Link) {
                return true;
            }

            if (!$current->targetExists()) {
                return $this->isPathInDirectory($current->getTargetPath());
            }

            $current = $current->getTarget();
        } while (count($paths) < Link::DEPTH_LIMIT);
        return false;
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