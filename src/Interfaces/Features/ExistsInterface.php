<?php

namespace Aternos\IO\Interfaces\Features;

interface ExistsInterface
{
    /**
     * Check if the IO Element exists
     *
     * @return bool
     */
    public function exists(): bool;
}