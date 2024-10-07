<?php

namespace Aternos\IO\Abstract\Buffer\Read;

use Aternos\IO\Exception\IOException;

/**
 * Trait BufferedReadSetPositionTrait
 *
 * Trait for buffered read classes that also implement the {@link SetPositionInterface}
 *
 * @package Aternos\IO\Abstract\Buffer
 */
trait BufferedReadSetPositionTrait
{
    use BufferedReadTrait {
        clearReadBuffer as protected clearReadBufferTrait;
    }

    /**
     * @inheritDoc
     */
    public function setPosition(int $position): static
    {
        if ($this->readBuffer) {
            $this->readBuffer->setPosition($position);
        } else {
            parent::setPosition($position);
        }
        return $this;
    }

    /**
     * @return $this
     * @throws IOException
     */
    public function clearReadBuffer(): static
    {
        if ($this->readBuffer) {
            parent::setPosition($this->readBuffer->getPosition());
        }
        return $this->clearReadBufferTrait();
    }
}