<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Interfaces\IOElementInterface;

/**
 * Interface CreateInterface
 *
 * Allows creating an element
 *
 * @package Aternos\IO\Interfaces\Features
 */
interface CreateInterface extends IOElementInterface
{
    /**
     * Create the element
     *
     * @return $this
     */
    public function create(): static;
}