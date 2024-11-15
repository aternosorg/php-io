<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Interfaces\IOElementInterface;

/**
 * Interface GetBirthTimestampInterface
 *
 * Allows getting the birth timestamp of an element
 *
 * @package Aternos\IO\Interfaces\Features
 */
interface GetBirthTimestampInterface extends IOElementInterface
{
    /**
     * Get the birth timestamp of the element
     *
     * @throws IOException
     * @return int
     */
    public function getBirthTimestamp(): int;
}