<?php

namespace Aternos\IO\Interfaces;

/**
 * Interface IOElementInterface
 *
 * Base interface for all elements
 *
 * @package Aternos\IO\Interfaces
 */
interface IOElementInterface
{
    /**
     * Get the name of an element
     *
     * @return ?string
     */
    public function getName(): ?string;
}