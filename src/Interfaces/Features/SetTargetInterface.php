<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Interfaces\IOElementInterface;

interface SetTargetInterface
{
    /**
     * @param IOElementInterface $target
     * @return $this
     */
    public function setTarget(IOElementInterface $target): static;
}