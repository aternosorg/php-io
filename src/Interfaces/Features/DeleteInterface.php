<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Interfaces\IOElementInterface;

interface DeleteInterface extends IOElementInterface
{
    /**
     * @return $this
     */
    public function delete(): static;
}