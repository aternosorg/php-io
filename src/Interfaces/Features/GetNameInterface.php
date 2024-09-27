<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Interfaces\IOElementInterface;

/**
 * Interface GetNameInterface
 *
 * Allows getting the name of an element, e.g. the name of a file in a directory or
 * a type name like "socket read stream"
 *
 * @package Aternos\IO\Interfaces\Features
 */
interface GetNameInterface extends IOElementInterface
{
    /**
     * Get the name of the element, e.g. the name of a file in a directory or
     * a type name like "socket read stream"
     *
     * @return string
     */
    public function getName(): string;
}