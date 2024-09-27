<?php

namespace Aternos\IO\Interfaces\Features;

/**
 * Interface ReadInterface
 *
 * Allows reading from the element
 *
 * @package Aternos\IO\Interfaces\Features
 */
interface ReadInterface extends GetPositionInterface
{
    /**
     * Read $length bytes from the element
     *
     * @param int $length
     * @return string
     */
    public function read(int $length): string;
}