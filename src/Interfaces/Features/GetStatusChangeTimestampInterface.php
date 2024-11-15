<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Interfaces\IOElementInterface;

/**
 * Interface GetStatusChangeTimestampInterface
 *
 * Allows getting the timestamp of the last status change
 *
 * @package Aternos\IO\Interfaces\Features
 */
interface GetStatusChangeTimestampInterface extends IOElementInterface
{
    /**
     * Get the timestamp of the last status change
     *
     * @throws IOException
     * @return int
     */
    public function getStatusChangeTimestamp(): int;
}