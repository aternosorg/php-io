<?php

namespace Aternos\IO\System\Link;

use Aternos\IO\Exception\DeleteException;
use Aternos\IO\Exception\GetTargetException;
use Aternos\IO\Exception\SetTargetException;
use Aternos\IO\Interfaces\Features\GetPathInterface;
use Aternos\IO\Interfaces\Types\FileInterface;
use Aternos\IO\Interfaces\Types\Link\FileLinkInterface;
use Aternos\IO\System\File\File;

/**
 * Class FileLink
 *
 * Filesystem link to a file
 *
 * @package Aternos\IO\System\Link
 */
class FileLink extends Link implements FileLinkInterface
{
    /**
     * @inheritDoc
     * @throws GetTargetException
     */
    public function close(): static
    {
        $this->getTarget()->close();
        return $this;
    }

    /**
     * @inheritDoc
     * @throws GetTargetException
     */
    public function getTarget(): FileInterface
    {
        if ($this->target) {
            return $this->target;
        }

        $targetPath = $this->getTargetPath();

        if (!$this->targetExists()) {
            return $this->target = new File($targetPath);
        }

        $target = static::getIOElementFromPath($targetPath);
        if (!$target instanceof FileInterface) {
            throw new GetTargetException("Could not get file link target because link target is not a file (" . $this->path . ")", $this);
        }
        return $this->target = $target;
    }

    /**
     * @inheritDoc
     * @throws SetTargetException
     * @throws DeleteException
     */
    public function setTarget(GetPathInterface $target): static
    {
        if (!$target instanceof FileInterface) {
            throw new SetTargetException("Could not set file link target because target is not a file (" . $this->path . " -> " . $target->getPath() . ")", $this);
        }
        return parent::setTarget($target);
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
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
     * @inheritDoc
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
     * @inheritDoc
     * @throws GetTargetException
     */
    public function isEndOfFile(): bool
    {
        return $this->getTarget()->isEndOfFile();
    }
}