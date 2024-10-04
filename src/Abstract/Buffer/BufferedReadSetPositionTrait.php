<?php

namespace Aternos\IO\Abstract\Buffer;

/**
 * Trait BufferedReadSetPositionTrait
 *
 * Trait for buffered read classes that also implement the {@link SetPositionInterface}
 *
 * @package Aternos\IO\Abstract\Buffer
 */
trait BufferedReadSetPositionTrait
{
    use BufferedReadTrait;

    public function setPosition(int $position): static
    {
        if ($this->readBuffer) {
            $this->readBuffer->setPosition($position);
        } else {
            parent::setPosition($position);
        }
        return $this;
    }
}