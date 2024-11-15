<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Interfaces\IOElementInterface;

/**
 * Interface SetModificationTimestampInterface
 *
 * Allows setting the modification timestamp of an element
 *
 * @package Aternos\IO\Interfaces\Features
 */
interface SetModificationTimestampInterface extends IOElementInterface
{
    /**
     * Set the modification timestamp
     *
     * This might also change other timestamps
     *
     * @throws IOException
     * @param int $timestamp
     * @return $this
     */
    public function setModificationTimestamp(int $timestamp): static;
}