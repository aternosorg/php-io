<?php

namespace Aternos\IO\Abstract\Buffer;

use Aternos\IO\Interfaces\Features\ReadInterface;

/**
 * Interface BufferedReadInterface
 *
 * Allows reading data into a buffer that can be read from in future read calls
 *
 * @package Aternos\IO\Abstract\Buffer
 */
interface BufferedReadInterface extends ReadInterface
{
    /**
     * Read data into a buffer that can be read from in future read calls
     *
     * @param int $length
     * @return $this
     */
    public function readIntoBuffer(int $length): static;
}