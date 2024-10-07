<?php

namespace Aternos\IO\Abstract\Buffer\Write;

trait BufferedWriteSetPositionTrait
{
    use BufferedWriteTrait {
        flushWriteBuffer as protected flushWriteBufferTrait;
    }

    /**
     * @inheritDoc
     */
    public function setPosition(int $position): static
    {
        if ($this->writeBuffer) {
            if ($this->writeBuffer->isInBuffer($position)) {
                $this->writeBuffer->setPosition($position);
                return $this;
            } else {
                $this->flushWriteBuffer();
            }
        }
        parent::setPosition($position);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function flushWriteBuffer(): static
    {
        if ($this->writeBuffer) {
            $position = $this->getPosition();
            $this->flushWriteBufferTrait();
            $this->setPosition($position);
        }
        return $this;
    }
}