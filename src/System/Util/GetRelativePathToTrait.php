<?php

namespace Aternos\IO\System\Util;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Exception\PathOutsideElementException;
use Aternos\IO\Interfaces\Features\GetPathInterface;

/**
 * Trait GetRelativePathToTrait
 *
 * Implements getRelativePathTo method
 *
 * @package Aternos\IO\System\Util
 */
trait GetRelativePathToTrait
{
    /**
     * Get the relative path to another element
     *
     * @param GetPathInterface $element
     * @param bool $allowOutsideElement Allow paths outside the element, throws an exception otherwise
     * @return string
     * @throws PathOutsideElementException
     * @throws IOException
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
}