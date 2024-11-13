<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Exception\IOException;

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
     * @throws IOException
     * @param string $buffer
     * @return $this
     */
    public function write(string $buffer): static;
}