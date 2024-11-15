<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Interfaces\IOElementInterface;

/**
 * Interface SetStatusChangeTimestampInterface
 *
 * Allows setting the status change timestamp of an element
 *
 * @package Aternos\IO\Interfaces\Features
 */
interface SetStatusChangeTimestampInterface extends IOElementInterface
{
    /**
     * Set the status change timestamp
     *
     * This might also change other timestamps
     *
     * @throws IOException
     * @param int $timestamp
     * @return $this
     */
    public function setStatusChangeTimestamp(int $timestamp): static;
}