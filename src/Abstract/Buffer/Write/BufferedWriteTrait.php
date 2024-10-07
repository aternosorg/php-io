<?php

namespace Aternos\IO\Abstract\Buffer\Write;

use Aternos\IO\Abstract\Buffer\Buffer;
use Aternos\IO\Exception\IOException;

/**
 * Trait BufferedWriteTrait
 *
 * Trait for buffered write classes implementing the {@link BufferedWriteInterface}
 *
 * @package Aternos\IO\Abstract\Buffer\Write
 */
trait BufferedWriteTrait
{
    protected ?Buffer $writeBuffer = null;
    protected ?int $maxWriteBufferLength = null;

    /**
     * @inheritDoc
     */
    public function write(string $buffer): static
    {
        if (!$this->writeBuffer) {
            $this->writeBuffer = new Buffer($this->getPosition());
        }

        if ($this->writeBuffer->isInBuffer($this->getPosition(), true)) {
            $this->writeBuffer->write($buffer);
        } else {
            $this->flushWriteBuffer();
            $this->write($buffer);
            return $this;
        }

        if ($this->maxWriteBufferLength && $this->writeBuffer->getLength() > $this->maxWriteBufferLength) {
            $this->flushWriteBuffer();
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getPosition(): int
    {
        /** @noinspection PhpMultipleClassDeclarationsInspection */
        return $this->writeBuffer ? $this->writeBuffer->getPosition() : parent::getPosition();
    }

    /**
     * @inheritDoc
     */
    public function setMaxWriteBufferLength(?int $maxWriteBufferLength): static
    {
        $this->maxWriteBufferLength = $maxWriteBufferLength;
        return $this;
    }

    /**
     * @inheritDoc
     * @throws IOException
     */
    public function flushWriteBuffer(): static
    {
        if ($this->writeBuffer) {
            /** @noinspection PhpMultipleClassDeclarationsInspection */
            parent::write($this->writeBuffer->getBuffer());
            $this->writeBuffer = null;
        }
        return $this;
    }

    /**
     * @throws IOException
     */
    public function __destruct()
    {
        $this->flushWriteBuffer();
        /** @noinspection PhpMultipleClassDeclarationsInspection */
        parent::__destruct();
    }
}