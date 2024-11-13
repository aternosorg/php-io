<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Exception\IOException;

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
     * @throws IOException
     * @param int $position
     * @return $this
     */
    public function setPosition(int $position): static;
}