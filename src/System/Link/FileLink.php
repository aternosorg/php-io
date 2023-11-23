<?php

namespace Aternos\IO\System\Link;

use Aternos\IO\Exception\GetTargetException;
use Aternos\IO\Exception\SetTargetException;
use Aternos\IO\Interfaces\IOElementInterface;
use Aternos\IO\Interfaces\Types\FileInterface;
use Aternos\IO\Interfaces\Types\Link\FileLinkInterface;

class FileLink extends Link implements FileLinkInterface
{
    /**
     * @throws GetTargetException
     */
    public function close(): static
    {
        $this->getTarget()->close();
        return $this;
    }

    /**
     * @return FileInterface
     * @throws GetTargetException
     */
    public function getTarget(): FileInterface
    {
        $target = parent::getTarget();
        if (!$target instanceof FileInterface) {
            throw new GetTargetException("Could not get file link target because link target is not a file (" . $this->path . ")", $this);
        }
        return $target;
    }

    public function setTarget(IOElementInterface $target): static
    {
        if (!$target instanceof FileInterface) {
            throw new SetTargetException("Could not set file link target because target is not a file (" . $this->path . " -> " . $target->getPath() . ")", $this);
        }
        return parent::setTarget($target);
    }

    /**
     * @throws GetTargetException
     */
    public function create(): static
    {
        $this->getTarget()->create();
        return $this;
    }

    /**
     * @inheritDoc
     * @throws GetTargetException
     */
    public function getPosition(): int
    {
        return $this->getTarget()->getPosition();
    }

    /**
     * @throws GetTargetException
     */
    public function getSize(): int
    {
        return $this->getTarget()->getSize();
    }

    /**
     * @inheritDoc
     * @throws GetTargetException
     */
    public function read(int $length): string
    {
        return $this->getTarget()->read($length);
    }

    /**
     * @throws GetTargetException
     */
    public function setPosition(int $position): static
    {
        $this->getTarget()->setPosition($position);
        return $this;
    }

    /**
     * @inheritDoc
     * @throws GetTargetException
     */
    public function truncate(int $size = 0): static
    {
        $this->getTarget()->truncate($size);
        return $this;
    }

    /**
     * @inheritDoc
     * @throws GetTargetException
     */
    public function write(string $buffer): static
    {
        $this->getTarget()->write($buffer);
        return $this;
    }

    /**
     * @throws GetTargetException
     */
    public function isEndOfFile(): bool
    {
        return $this->getTarget()->isEndOfFile();
    }
}