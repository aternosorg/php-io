<?php

namespace Aternos\IO\Abstract\Buffer\Read;

use Aternos\IO\Abstract\Buffer\Buffer;
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
    protected ?int $automaticReadBufferLength = null;

    /**
     * @inheritDoc
     */
    public function read(int $length): string
    {
        $data = "";

        if ($this->readBuffer && $this->readBuffer->isInBuffer($this->getPosition())) {
            $data = $this->readBuffer->read($length);
        }

        if (strlen($data) >= $length) {
            return $data;
        }

        if ($this->automaticReadBufferLength) {
            $this->readIntoBuffer($this->automaticReadBufferLength);
            return $this->read($length);
        }

        $this->readBuffer = null;
        /** @noinspection PhpMultipleClassDeclarationsInspection */
        $data .= parent::read($length - strlen($data));

        return $data;
    }

    /**
     * @inheritDoc
     * @throws IOException
     */
    public function readIntoBuffer(int $length): static
    {
        /** @noinspection PhpMultipleClassDeclarationsInspection */
        $this->readBuffer = new Buffer($this->getPosition(), parent::read($length));
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function clearReadBuffer(): static
    {
        $this->readBuffer = null;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setAutomaticReadBufferLength(?int $length): static
    {
        $this->automaticReadBufferLength = $length;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getPosition(): int
    {
        /** @noinspection PhpMultipleClassDeclarationsInspection */
        return $this->readBuffer?->getPosition() ?? parent::getPosition();
    }
}