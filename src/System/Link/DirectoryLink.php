<?php

namespace Aternos\IO\System\Link;

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
     * @param bool $allowOutsideLinks
     * @return Generator
     * @throws GetTargetException
     */
    public function getChildren(bool $allowOutsideLinks = false): Generator
    {
        yield from $this->getTarget()->getChildren($allowOutsideLinks);
    }

    /**
     * @param bool $allowOutsideLinks
     * @param bool $followLinks
     * @param int $currentDepth
     * @return Generator
     * @throws GetTargetException
     */
    public function getChildrenRecursive(bool $allowOutsideLinks = false, bool $followLinks = true, int $currentDepth = 0): Generator
    {
        yield from $this->getTarget()->getChildrenRecursive($allowOutsideLinks, $followLinks, $currentDepth);
    }
}