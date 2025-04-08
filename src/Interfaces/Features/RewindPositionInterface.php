<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Exception\IOException;

/**
 * Interface RewindPositionInterface
 *
 * Allows rewinding the seek position of an element to the beginning
 *
 * @package Aternos\IO\Interfaces\Features
 */
interface RewindPositionInterface extends GetPositionInterface
{
    /**
     * Rewind seek position of an element to the beginning
     *
     * @throws IOException
     * @return $this
     */
    public function rewindPosition(): static;
}