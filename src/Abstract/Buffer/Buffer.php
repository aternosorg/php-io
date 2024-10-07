<?php

namespace Aternos\IO\Abstract\Buffer;

/**
 * Class Buffer
 *
 * The buffer class represents partial element data that is hold in memory
 *
 * @package Aternos\IO\Abstract\Buffer
 */
class Buffer
{
    /**
     * @param int $absoluteStartPosition The position of the buffer in the element
     * @param string $buffer The buffer data
     * @param int $relativeBufferPosition The position in the buffer
     */
    public function __construct(
        protected int    $absoluteStartPosition = 0,
        protected string $buffer = "",
        protected int    $relativeBufferPosition = 0)
    {
    }

    /**
     * Get the absolute start position of the buffer in the element
     *
     * @return int
     */
    public function getAbsoluteStartPosition(): int
    {
        return $this->absoluteStartPosition;
    }

    /**
     * Set the absolute start position of the buffer in the element
     *
     * @param int $absoluteStartPosition
     * @return $this
     */
    public function setAbsoluteStartPosition(int $absoluteStartPosition): static
    {
        $this->absoluteStartPosition = $absoluteStartPosition;
        return $this;
    }

    /**
     * Get the buffer data
     *
     * @return string
     */
    public function getBuffer(): string
    {
        return $this->buffer;
    }

    /**
     * Set the buffer data
     *
     * @param string $buffer
     * @return $this
     */
    public function setBuffer(string $buffer): static
    {
        $this->buffer = $buffer;
        return $this;
    }

    /**
     * Get the relative position in the buffer
     *
     * @return int
     */
    public function getRelativeBufferPosition(): int
    {
        return $this->relativeBufferPosition;
    }

    /**
     * Set the relative position in the buffer
     *
     * @param int $relativeBufferPosition
     * @return $this
     */
    public function setRelativeBufferPosition(int $relativeBufferPosition): static
    {
        $this->relativeBufferPosition = $relativeBufferPosition;
        return $this;
    }

    /**
     * Get the absolute current position in the element
     *
     * @return int
     */
    public function getPosition(): int
    {
        return $this->getAbsoluteStartPosition() + $this->getRelativeBufferPosition();
    }

    /**
     * Set the absolute current position in the element
     *
     * @param int $position
     * @return void
     */
    public function setPosition(int $position): void
    {
        $this->relativeBufferPosition = $position - $this->getAbsoluteStartPosition();
    }

    /**
     * Check if a position is in the buffer
     *
     * @param int $position
     * @param bool $includeEnd
     * @return bool
     */
    public function isInBuffer(int $position, bool $includeEnd = false): bool
    {
        if ($position < $this->getAbsoluteStartPosition()) {
            return false;
        }

        $endPosition = $this->getAbsoluteStartPosition() + strlen($this->getBuffer());

        if ($includeEnd) {
            return $position <= $endPosition;
        } else {
            return $position < $endPosition;
        }
    }

    /**
     * Read data from the buffer
     *
     * @param int $length
     * @return string
     */
    public function read(int $length): string
    {
        $data = substr($this->getBuffer(), $this->getRelativeBufferPosition(), $length);
        $this->relativeBufferPosition += strlen($data);
        return $data;
    }

    /**
     * @param string $data
     * @return $this
     */
    public function write(string $data): static
    {
        $this->buffer = substr_replace($this->buffer, $data, $this->relativeBufferPosition);
        $this->relativeBufferPosition += strlen($data);
        return $this;
    }

    /**
     * @return int
     */
    public function getLength(): int
    {
        return strlen($this->buffer);
    }
}