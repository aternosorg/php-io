<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Interfaces\IOElementInterface;

interface TargetExistsInterface extends IOElementInterface
{
    /**
     * @return bool
     */
    public function targetExists(): bool;
}