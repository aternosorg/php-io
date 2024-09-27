<?php

namespace Aternos\IO\Interfaces\Features;

/**
 * Interface TruncateInterface
 *
 * Allows truncating an element
 *
 * @package Aternos\IO\Interfaces\Features
 */
interface TruncateInterface extends GetPositionInterface
{
    /**
     * Truncate the element to the given size
     *
     * @param int $size
     * @return $this
     */
    public function truncate(int $size = 0): static;
}