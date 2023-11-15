<?php

namespace Aternos\IO\Interfaces\Features;

interface WriteInterface extends GetPositionInterface
{
    /**
     * @param string $buffer
     * @return $this
     */
    public function write(string $buffer): static;
}