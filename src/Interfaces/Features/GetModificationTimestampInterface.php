<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Interfaces\IOElementInterface;

/**
 * Interface GetModificationTimestampInterface
 *
 * Allows getting the modification timestamp of an element
 *
 * @package Aternos\IO\Interfaces\Features
 */
interface GetModificationTimestampInterface extends IOElementInterface
{
    /**
     * Get the modification timestamp of the element
     *
     * @throws IOException
     * @return int
     */
    public function getModificationTimestamp(): int;
}