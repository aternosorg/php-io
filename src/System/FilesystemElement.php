<?php

namespace Aternos\IO\System;

use Aternos\IO\Exception\MoveException;
use Aternos\IO\Exception\PathOutsideElementException;
use Aternos\IO\Interfaces\Features\GetPathInterface;
use Aternos\IO\System\Directory\Directory;
use Aternos\IO\System\File\File;
use Aternos\IO\System\Link\DirectoryLink;
use Aternos\IO\System\Link\FileLink;
use Aternos\IO\System\Link\Link;

/**
 * Class FilesystemElement
 *
 * Base class for filesystem elements
 *
 * @package Aternos\IO\System
 */
abstract class FilesystemElement implements FilesystemInterface
{
    /**
     * Get the matching filesystem element for a path
     *
     * @param string $path
     * @return FilesystemElement
     */
    public static function getIOElementFromPath(string $path): FilesystemElement
    {
        if (is_link($path)) {
            if (!file_exists($path)) {
                return new Link($path);
            }
            if (is_dir($path)) {
                return new DirectoryLink($path);
            }
            return new FileLink($path);
        }
        if (is_dir($path)) {
            return new Directory($path);
        }
        return new File($path);
    }

    /**
     * @param string $path
     */
    public function __construct(protected string $path)
    {
    }

    /**
     * @inheritDoc
     * @throws MoveException
     */
    public function changeName(string $name): static
    {
        $this->move(dirname($this->path) . DIRECTORY_SEPARATOR . $name);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return basename($this->path);
    }

    /**
     * @inheritDoc
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @inheritDoc
     * @throws PathOutsideElementException
     */
    public function getRelativePathTo(GetPathInterface $element, bool $allowOutsideElement = false): string
    {
        $sourcePath = $element->getPath();
        $targetPath = $this->getPath();

        $sourcePathParts = explode(DIRECTORY_SEPARATOR, $sourcePath);
        $targetPathParts = explode(DIRECTORY_SEPARATOR, $targetPath);

        foreach ($sourcePathParts as $key => $sourcePathPart) {
            if (isset($targetPathParts[$key]) && $targetPathParts[$key] === $sourcePathPart) {
                unset($sourcePathParts[$key]);
                unset($targetPathParts[$key]);
                continue;
            }

            if (!$allowOutsideElement) {
                throw new PathOutsideElementException("Path is outside of element (" . $sourcePath . " -> " . $targetPath . ")", $this);
            }
            break;
        }

        $relativePath = "";
        foreach ($sourcePathParts as $ignored) {
            $relativePath .= ".." . DIRECTORY_SEPARATOR;
        }
        foreach ($targetPathParts as $targetPathPart) {
            $relativePath .= $targetPathPart . DIRECTORY_SEPARATOR;
        }
        return rtrim($relativePath, DIRECTORY_SEPARATOR);
    }

    /**
     * @inheritDoc
     * @throws MoveException
     */
    public function move(string $path): static
    {
        if (!@rename($this->path, $path)) {
            $error = error_get_last();
            throw new MoveException("Could not move element (" . $this->path . " -> " . $path . ")" . ($error ? ": " . $error["message"] : ""), $this);
        }
        $this->path = $path;
        return $this;
    }

    /**
     * @inheritDoc
     * @return bool
     */
    public function exists(): bool
    {
        return file_exists($this->path);
    }

    /**
     * @return string[]
     */
    public function __serialize(): array
    {
        return [
            "path" => $this->path
        ];
    }
}