<?php

namespace Aternos\IO\Filesystem\Link;

use Aternos\IO\Exception\DeleteException;
use Aternos\IO\Exception\GetTargetException;
use Aternos\IO\Exception\SetTargetException;
use Aternos\IO\Interfaces\IOElementInterface;
use Aternos\IO\Interfaces\Types\DirectoryInterface;
use Generator;

class DirectoryLink extends Link implements DirectoryInterface
{
    /**
     * @return DirectoryInterface
     * @throws GetTargetException
     */
    public function getTarget(): DirectoryInterface
    {
        $target = parent::getTarget();
        if (!$target instanceof DirectoryInterface) {
            throw new GetTargetException("Could not get directory link target because link target is not a directory (" . $this->path . ")", $this);
        }
        return $target;
    }

    /**
     * @param IOElementInterface $target
     * @return $this
     * @throws SetTargetException
     * @throws DeleteException
     */
    public function setTarget(IOElementInterface $target): static
    {
        if (!$target instanceof DirectoryInterface) {
            throw new SetTargetException("Could not set directory link target because target is not a directory (" . $this->path . " -> " . $target->getPath() . ")", $this);
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
     * @throws GetTargetException
     */
    public function getChildren(): Generator
    {
        yield from $this->getTarget()->getChildren();
    }

    /**
     * @return Generator
     * @throws GetTargetException
     */
    public function getChildrenRecursive(): Generator
    {
        yield from $this->getTarget()->getChildrenRecursive();
    }
}