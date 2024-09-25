<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Interfaces\IOElementInterface;

interface SetTargetInterface extends IOElementInterface
{
    /**
     * @param IOElementInterface $target
     * @return $this
     */
    public function setTarget(IOElementInterface $target): static;
}