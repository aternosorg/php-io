<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Interfaces\IOElementInterface;

interface ExistsInterface extends IOElementInterface
{
    /**
     * Check if the IO Element exists
     *
     * @return bool
     */
    public function exists(): bool;
}