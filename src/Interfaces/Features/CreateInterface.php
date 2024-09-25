<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Interfaces\IOElementInterface;

interface CreateInterface extends IOElementInterface
{
    /**
     * @return $this
     */
    public function create(): static;
}