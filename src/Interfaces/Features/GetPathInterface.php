<?php

namespace Aternos\IO\Interfaces\Features;

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
     * @return string
     */
    public function getPath(): string;

    /**
     * Get the relative path to another element
     *
     * @param GetPathInterface $element
     * @param bool $allowOutsideElement Allow paths outside the element, throws an exception otherwise
     * @return string
     */
    public function getRelativePathTo(GetPathInterface $element, bool $allowOutsideElement = false): string;
}