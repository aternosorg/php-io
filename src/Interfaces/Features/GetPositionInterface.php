<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Interfaces\IOElementInterface;

/**
 * Interface GetPositionInterface
 *
 * Allows getting the current seek position of an element
 *
 * @package Aternos\IO\Interfaces\Features
 */
interface GetPositionInterface extends IOElementInterface
{
    /**
     * Get the current seek position
     *
     * @return int
     */
    public function getPosition(): int;
}