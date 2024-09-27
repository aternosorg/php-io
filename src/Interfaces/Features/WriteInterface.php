<?php

namespace Aternos\IO\Interfaces\Features;

/**
 * Interface WriteInterface
 *
 * Allows writing to an element
 *
 * @package Aternos\IO\Interfaces\Features
 */
interface WriteInterface extends GetPositionInterface
{
    /**
     * Write a buffer to the element
     *
     * @param string $buffer
     * @return $this
     */
    public function write(string $buffer): static;
}