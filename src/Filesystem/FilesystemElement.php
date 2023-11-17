<?php

namespace Aternos\IO\Filesystem;

use Aternos\IO\Exception\MoveException;
use Aternos\IO\Exception\PathOutsideElementException;
use Aternos\IO\Interfaces\Features\GetPathInterface;
use Aternos\IO\Interfaces\IOElementInterface;

abstract class FilesystemElement implements FilesystemInterface, IOElementInterface
{
    /**
     * @param string $path
     * @return IOElementInterface
     */
    public static function getIOElementFromPath(string $path): IOElementInterface
    {
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
     * @param string $name
     * @return $this
     * @throws MoveException
     */
    public function changeName(string $name): static
    {
        $this->move(dirname($this->path) . DIRECTORY_SEPARATOR . $name);
        return $this;
    }

    public function getName(): string
    {
        return basename($this->path);
    }

    public function getPath(): string
    {
        return $this->path;
    }

    /**
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
     * @return bool
     */
    public function exists(): bool
    {
        return file_exists($this->path);
    }
}