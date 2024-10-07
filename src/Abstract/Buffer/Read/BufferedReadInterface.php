<?php

namespace Aternos\IO\Abstract\Buffer\Read;

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

    /**
     * The length of data to read automatically into the buffer when the buffer is empty
     *
     * @param int|null $length
     * @return $this
     */
    public function setAutomaticReadBufferLength(?int $length): static;

    /**
     * Clear the read buffer
     *
     * The position in the element might still be at the end of the buffer if changing the position is not supported
     *
     * @return $this
     */
    public function clearReadBuffer(): static;
}