<?php

namespace Aternos\IO\Interfaces\Features;

interface IsEndOfFileInterface
{
    /**
     * Check if the current position is at the end of the file
     *
     * @return bool
     */
    public function isEndOfFile(): bool;
}