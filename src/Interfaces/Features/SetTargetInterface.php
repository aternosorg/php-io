<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Interfaces\IOElementInterface;

/**
 * Interface SetTargetInterface
 *
 * Allows setting the target of a link element
 *
 * @package Aternos\IO\Interfaces\Features
 */
interface SetTargetInterface extends IOElementInterface
{
    /**
     * Set the target of a link element
     *
     * @param GetPathInterface $target
     * @return $this
     */
    public function setTarget(GetPathInterface $target): static;
}