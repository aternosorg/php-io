<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Interfaces\IOElementInterface;

/**
 * Interface SetBirthTimestampInterface
 *
 * Allows setting the birth timestamp of an element
 *
 * @package Aternos\IO\Interfaces\Features
 */
interface SetBirthTimestampInterface extends IOElementInterface
{
    /**
     * Set the birth timestamp
     *
     * This might also change other timestamps
     *
     * @throws IOException
     * @param int $timestamp
     * @return $this
     */
    public function setBirthTimestamp(int $timestamp): static;
}