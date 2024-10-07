<?php

namespace Aternos\IO\Abstract\Buffer\Write;

use Aternos\IO\Interfaces\Features\WriteInterface;

interface BufferedWriteInterface extends WriteInterface
{
    /**
     * Write the data from the write buffer to the actual element
     *
     * @return $this
     */
    public function flushWriteBuffer(): static;

    /**
     * Set the maximum length of the write buffer
     *
     * When a write operation exceeds this length, the write buffer will be flushed
     *
     * @param int|null $maxWriteBufferLength
     * @return $this
     */
    public function setMaxWriteBufferLength(?int $maxWriteBufferLength): static;
}