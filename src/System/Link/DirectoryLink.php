<?php

namespace Aternos\IO\System\Link;

use Aternos\IO\Exception\DeleteException;
use Aternos\IO\Exception\GetTargetException;
use Aternos\IO\Exception\SetTargetException;
use Aternos\IO\Interfaces\Features\GetPathInterface;
use Aternos\IO\Interfaces\IOElementInterface;
use Aternos\IO\Interfaces\Types\DirectoryInterface;
use Aternos\IO\Interfaces\Types\Link\DirectoryLinkInterface;
use Aternos\IO\System\Directory\Directory;
use Generator;

/**
 * Class DirectoryLink
 *
 * Filesystem link to a directory
 *
 * @package Aternos\IO\System\Link
 */
class DirectoryLink extends Link implements DirectoryLinkInterface
{
    /**
     * @inheritDoc
     * @throws GetTargetException
     */
    public function getTarget(): DirectoryInterface
    {
        if ($this->target) {
            return $this->target;
        }

        $targetPath = $this->getTargetPath();

        if (!$this->targetExists()) {
            return $this->target = new Directory($targetPath);
        }

        $target = static::getIOElementFromPath($targetPath);
        if (!$target instanceof DirectoryInterface) {
            throw new GetTargetException("Could not get directory link target because link target is not a directory (" . $this->path . ")", $this);
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
        if (!$target instanceof DirectoryInterface) {
            throw new SetTargetException("Could not set directory link target because target is not a directory (" . $this->path . " -> " . $target->getPath() . ")", $this);
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
    public function getChildren(bool $allowOutsideLinks = false): Generator
    {
        yield from $this->getTarget()->getChildren($allowOutsideLinks);
    }

    /**
     * @inheritDoc
     * @throws GetTargetException
     */
    public function getChildrenRecursive(bool $allowOutsideLinks = false, bool $followLinks = true, int $currentDepth = 0): Generator
    {
        yield from $this->getTarget()->getChildrenRecursive($allowOutsideLinks, $followLinks, $currentDepth);
    }

    /**
     * @inheritDoc
     */
    public function getChild(string $name, string ...$features): IOElementInterface
    {
        return $this->getTarget()->getChild($name, ...$features);
    }
}