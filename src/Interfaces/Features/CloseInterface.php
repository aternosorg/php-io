<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Interfaces\IOElementInterface;

interface CloseInterface extends IOElementInterface
{
    /**
     * @return $this
     */
    public function close(): static;
}