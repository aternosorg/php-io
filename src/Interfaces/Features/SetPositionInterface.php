<?php

namespace Aternos\IO\Interfaces\Features;

interface SetPositionInterface extends GetPositionInterface
{
    /**
     * @param int $position
     * @return $this
     */
    public function setPosition(int $position): static;
}