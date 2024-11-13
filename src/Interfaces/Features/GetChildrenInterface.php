<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Interfaces\IOElementInterface;
use Generator;

/**
 * Interface GetChildrenInterface
 *
 * Allows getting children of an element, e.g. a directory
 *
 * @package Aternos\IO\Interfaces\Features
 */
interface GetChildrenInterface extends IOElementInterface
{
    /**
     * Get the direct children of this element
     *
     * @throws IOException
     * @param bool $allowOutsideLinks If true, links pointing outside the element are allowed
     * @return Generator<IOElementInterface>
     */
    public function getChildren(bool $allowOutsideLinks = false): Generator;

    /**
     * Get all children of this element recursively
     *
     * @throws IOException
     * @param bool $allowOutsideLinks If true, links pointing outside the element are allowed
     * @param bool $followLinks If true, links are followed
     * @param int $currentDepth The current depth of the recursion (used internally)
     * @return Generator<IOElementInterface>
     */
    public function getChildrenRecursive(bool $allowOutsideLinks = false, bool $followLinks = true, int $currentDepth = 0): Generator;
}