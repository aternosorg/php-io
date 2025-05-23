<?php

namespace Aternos\IO\System\Directory;

use Aternos\IO\Exception\CreateDirectoryException;
use Aternos\IO\Exception\DeleteException;
use Aternos\IO\Exception\GetTargetException;
use Aternos\IO\Exception\IOException;
use Aternos\IO\Exception\MissingPermissionsException;
use Aternos\IO\Interfaces\Features\GetChildrenInterface;
use Aternos\IO\Interfaces\Features\GetPathInterface;
use Aternos\IO\Interfaces\Features\GetTargetInterface;
use Aternos\IO\Interfaces\IOElementInterface;
use Aternos\IO\Interfaces\Types\DirectoryInterface;
use Aternos\IO\System\File\File;
use Aternos\IO\System\FilesystemElement;
use Aternos\IO\System\Link\DirectoryLink;
use Aternos\IO\System\Link\FileLink;
use Aternos\IO\System\Link\Link;
use DirectoryIterator;
use Generator;
use InvalidArgumentException;

/**
 * Class Directory
 *
 * Filesystem directory
 *
 * @package Aternos\IO\System\Directory
 */
class Directory extends FilesystemElement implements DirectoryInterface
{
    public const int MAX_DEPTH = 100;

    /**
     * @inheritDoc
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
     * @inheritDoc
     * @return FilesystemElement
     */
    public function getChild(string $name, string ...$features): FilesystemElement
    {
        /** @var class-string<FilesystemElement>[] $supportedChildClasses */
        $supportedChildClasses = [
            Directory::class,
            DirectoryLink::class,
            File::class,
            FileLink::class,
            Link::class
        ];

        /** @var class-string<FilesystemElement> $childClass */
        $childClass = $this->findInstanceOfAll($supportedChildClasses, $features);
        if (!$childClass) {
            throw new InvalidArgumentException("No supported child class found for features: " . implode(", ", $features));
        }
        return new $childClass($this->getPath() . DIRECTORY_SEPARATOR . $name);
    }

    /**
     * Find a class that is an instance of all required classes
     *
     * @param class-string[] $availableClasses
     * @param class-string[] $requiredClasses
     * @return string|null
     */
    protected function findInstanceOfAll(array $availableClasses, array $requiredClasses): ?string
    {
        foreach ($availableClasses as $availableClass) {
            if ($this->isInstanceOfAll($availableClass, $requiredClasses)) {
                return $availableClass;
            }
        }
        return null;
    }

    /**
     * Check if a class is an instance of all required classes
     *
     * @param class-string $class
     * @param class-string[] $requiredClasses
     * @return bool
     */
    protected function isInstanceOfAll(string $class, array $requiredClasses): bool
    {
        foreach ($requiredClasses as $requiredClass) {
            if (!is_a($class, $requiredClass, true)) {
                return false;
            }
        }
        return true;
    }


    /**
     * @inheritDoc
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
     * Check if an element is in this directory
     *
     * @param GetPathInterface $element
     * @return bool
     * @throws GetTargetException
     * @throws IOException
     */
    protected function isElementInDirectory(GetPathInterface $element): bool
    {
        if ($element instanceof Link) {
            return $this->isLinkInDirectory($element);
        }
        return $this->isPathInDirectory($element->getPath());
    }

    /**
     * Check if a link and all links up to the final target are in this directory
     *
     * @param Link $link
     * @return bool
     * @throws GetTargetException
     * @throws IOException
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
     * Check if a path is in this directory
     *
     * @param string $path
     * @return bool
     * @throws IOException
     */
    protected function isPathInDirectory(string $path): bool
    {
        if (!str_ends_with($path, "/")) {
            $path .= "/";
        }
        return str_starts_with($path, $this->getPath());
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
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