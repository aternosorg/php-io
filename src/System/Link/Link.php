<?php

namespace Aternos\IO\System\Link;

use Aternos\IO\Exception\DeleteException;
use Aternos\IO\Exception\GetTargetException;
use Aternos\IO\Exception\IOException;
use Aternos\IO\Exception\SetTargetException;
use Aternos\IO\Interfaces\Features\GetPathInterface;
use Aternos\IO\Interfaces\Features\GetTargetPathInterface;
use Aternos\IO\Interfaces\IOElementInterface;
use Aternos\IO\Interfaces\Types\Link\LinkInterface;
use Aternos\IO\System\FilesystemElement;

/**
 * Class Link
 *
 * General filesystem link
 *
 * @package Aternos\IO\System\Link
 */
class Link extends FilesystemElement implements LinkInterface, GetTargetPathInterface
{
    public const int DEPTH_LIMIT = 40;
    protected ?IOElementInterface $target = null;
    protected ?bool $existsOverride = null;

    /**
     * @inheritDoc
     * @throws DeleteException
     */
    public function delete(): static
    {
        if (!$this->exists()) {
            return $this;
        }
        if (!@unlink($this->path)) {
            throw new DeleteException("Could not delete link (" . $this->path . ")", $this);
        }
        return $this;
    }

    /**
     * @inheritDoc
     * @throws GetTargetException
     */
    public function getTarget(): IOElementInterface
    {
        if ($this->target) {
            return $this->target;
        }

        $targetPath = $this->getTargetPath();

        if (!$this->targetExists()) {
            throw new GetTargetException("Could not get link target because target does not exist (" . $targetPath . ")", $this);
        }

        return $this->target = static::getIOElementFromPath($targetPath);
    }

    /**
     * @inheritDoc
     * @throws DeleteException
     * @throws SetTargetException
     */
    public function setTarget(GetPathInterface $target): static
    {
        if ($this->exists()) {
            $this->delete();
        }
        if (!@symlink($target->getPath(), $this->path)) {
            throw new SetTargetException("Could not set link target (" . $this->path . " -> " . $target->getPath() . ")", $this);
        }
        $this->target = $target;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function exists(): bool
    {
        if ($this->existsOverride !== null) {
            return $this->existsOverride;
        }
        return is_link($this->path);
    }

    /**
     * @inheritDoc
     * @throws GetTargetException
     */
    public function getTargetPath(): string
    {
        if (!$this->exists()) {
            throw new GetTargetException("Could not get link target because link does not exist (" . $this->path . ")", $this);
        }

        return readlink($this->path);
    }

    /**
     * @inheritDoc
     * @throws GetTargetException
     */
    public function targetExists(): bool
    {
        $targetPath = $this->getTargetPath();

        return file_exists($targetPath) || is_link($targetPath);
    }

    /**
     * @inheritDoc
     * @throws GetTargetException
     */
    public function getFinalTarget(): IOElementInterface
    {
        return $this->getFinalLink()->getTarget();
    }

    /**
     * Get the last link in the chain, detecting loops and too many levels of links
     *
     * @return LinkInterface
     * @throws GetTargetException
     * @throws IOException
     */
    protected function getFinalLink(): LinkInterface
    {
        $paths = [];
        $current = $this;
        do {
            if (in_array($current->getPath(), $paths)) {
                throw new GetTargetException("Could not get link target because of infinite link loop (" . $this->getPath() . ")", $this);
            }
            $paths[] = $current->getPath();

            if (!$current->targetExists()) {
                return $current;
            }
            $target = $current->getTarget();
            if (!$target instanceof LinkInterface) {
                return $current;
            }
            $current = $target;
        } while (count($paths) < static::DEPTH_LIMIT);

        throw new GetTargetException("Could not get link target because of too many levels of links (" . $this->getPath() . ")", $this);
    }

    /**
     * @inheritDoc
     * @throws GetTargetException
     */
    public function getFinalTargetPath(): string
    {
        return $this->getFinalLink()->getTargetPath();
    }

    /**
     * @return array|string[]
     */
    public function __serialize(): array
    {
        return [
            ...parent::__serialize(),
            "target" => $this->target
        ];
    }
}