<?php

namespace Aternos\IO\Abstract\Buffer;

use Aternos\IO\Exception\IOException;

/**
 * Trait BufferedReadTrait
 *
 * Trait for buffered read classes implementing the {@link BufferedReadInterface}
 *
 * @package Aternos\IO\Abstract\Buffer
 */
trait BufferedReadTrait
{
    protected ?Buffer $readBuffer = null;

    /**
     * @inheritDoc
     */
    public function read(int $length): string
    {
        $data = "";

        if ($this->readBuffer && $this->readBuffer->isInBuffer($this->getPosition())) {
            $data = $this->readBuffer->read($length);
        }

        if (strlen($data) < $length) {
            $this->readBuffer = null;
            $data .= parent::read($length - strlen($data));
        }

        return $data;
    }

    /**
     * @inheritDoc
     * @throws IOException
     */
    public function readIntoBuffer(int $length): static
    {
        $this->readBuffer = new Buffer($this->getPosition(), $this->read($length));
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getPosition(): int
    {
        return $this->readBuffer?->getPosition() ?? parent::getPosition();
    }
}