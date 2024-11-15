<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Interfaces\IOElementInterface;

/**
 * Interface GetAccessTimestampInterface
 *
 * Allows getting the access timestamp of an element
 *
 * @package Aternos\IO\Interfaces\Features
 */
interface GetAccessTimestampInterface extends IOElementInterface
{
    /**
     * Get the access timestamp of the element
     *
     * @throws IOException
     * @return int
     */
    public function getAccessTimestamp(): int;
}