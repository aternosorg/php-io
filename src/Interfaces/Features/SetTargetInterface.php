<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Interfaces\IOElementInterface;

interface SetTargetInterface extends IOElementInterface
{
    /**
     * @param GetPathInterface $target
     * @return $this
     */
    public function setTarget(GetPathInterface $target): static;
}