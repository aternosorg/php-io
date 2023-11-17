<?php

namespace Aternos\IO\Filesystem\Link;

use Aternos\IO\Exception\DeleteException;
use Aternos\IO\Exception\GetTargetException;
use Aternos\IO\Exception\SetTargetException;
use Aternos\IO\Filesystem\FilesystemElement;
use Aternos\IO\Interfaces\IOElementInterface;
use Aternos\IO\Interfaces\Types\Link\LinkInterface;

class Link extends FilesystemElement implements LinkInterface
{
    protected ?IOElementInterface $target = null;
    protected ?bool $existsOverride = null;

    /**
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
     * @throws GetTargetException
     */
    public function getTarget(): IOElementInterface
    {
        if ($this->target) {
            return $this->target;
        }

        if (!$this->exists()) {
            throw new GetTargetException("Could not get link target because link does not exist (" . $this->path . ")", $this);
        }

        $targetPath = readlink($this->path);

        if (!file_exists($targetPath)) {
            throw new GetTargetException("Could not get link target because target does not exist (" . $targetPath . ")", $this);
        }

        return $this->target = static::getIOElementFromPath($targetPath);
    }

    /**
     * @param IOElementInterface $target
     * @return $this
     * @throws DeleteException
     * @throws SetTargetException
     */
    public function setTarget(IOElementInterface $target): static
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
     * @return bool
     */
    public function exists(): bool
    {
        if ($this->existsOverride !== null) {
            return $this->existsOverride;
        }
        return is_link($this->path);
    }
}