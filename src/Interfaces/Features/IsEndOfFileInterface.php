<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Interfaces\IOElementInterface;

/**
 * Interface IsEndOfFileInterface
 *
 * Allows checking if the current position is at the end of the file
 *
 * @package Aternos\IO\Interfaces\Features
 */
interface IsEndOfFileInterface extends IOElementInterface
{
    /**
     * Check if the current position is at the end of the file
     *
     * @throws IOException
     * @return bool
     */
    public function isEndOfFile(): bool;
}