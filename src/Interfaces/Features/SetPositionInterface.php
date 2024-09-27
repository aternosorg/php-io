<?php

namespace Aternos\IO\Interfaces\Features;

/**
 * Interface SetPositionInterface
 *
 * Allows setting the seek position of an element
 *
 * @package Aternos\IO\Interfaces\Features
 */
interface SetPositionInterface extends GetPositionInterface
{
    /**
     * Set the seek position of an element
     *
     * @param int $position
     * @return $this
     */
    public function setPosition(int $position): static;
}