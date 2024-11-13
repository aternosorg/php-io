<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Exception\IOException;

/**
 * Interface GetPathInterface
 *
 * Allows getting the path of an element
 *
 * @package Aternos\IO\Interfaces\Features
 */
interface GetPathInterface extends GetNameInterface
{
    /**
     * Get the path of the element
     *
     * @throws IOException
     * @return string
     */
    public function getPath(): string;

    /**
     * Get the relative path to another element
     *
     * @throws IOException
     * @param GetPathInterface $element
     * @param bool $allowOutsideElement Allow paths outside the element, throws an exception otherwise
     * @return string
     */
    public function getRelativePathTo(GetPathInterface $element, bool $allowOutsideElement = false): string;
}