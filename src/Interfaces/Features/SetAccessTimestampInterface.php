<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Interfaces\IOElementInterface;

/**
 * Interface SetAccessTimestampInterface
 *
 * Allows setting the access timestamp of an element
 *
 * @package Aternos\IO\Interfaces\Features
 */
interface SetAccessTimestampInterface extends IOElementInterface
{
    /**
     * Set the access timestamp
     *
     * This might also change other timestamps
     *
     * @throws IOException
     * @param int $timestamp
     * @return $this
     */
    public function setAccessTimestamp(int $timestamp): static;
}