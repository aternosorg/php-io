<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Interfaces\IOElementInterface;

/**
 * Interface DeleteInterface
 *
 * Allows deleting an element
 *
 * @package Aternos\IO\Interfaces\Features
 */
interface DeleteInterface extends IOElementInterface
{
    /**
     * Delete the element
     *
     * @return $this
     */
    public function delete(): static;
}