<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Interfaces\IOElementInterface;

/**
 * Interface GetSizeInterface
 *
 * Allows getting the size of an element
 *
 * @package Aternos\IO\Interfaces\Features
 */
interface GetSizeInterface extends IOElementInterface
{
    /**
     * Get the size of the element
     *
     * @throws IOException
     * @return int
     */
    public function getSize(): int;
}