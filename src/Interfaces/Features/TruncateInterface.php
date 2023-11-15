<?php

namespace Aternos\IO\Interfaces\Features;

interface TruncateInterface extends GetPositionInterface
{
    /**
     * @param int $size
     * @return $this
     */
    public function truncate(int $size = 0): static;
}