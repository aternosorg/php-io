<?php

namespace Aternos\IO\Interfaces\Features;

/**
 * Interface ChangeNameInterface
 *
 * Allows changing the name of an element, e.g. the name of a file in a directory
 *
 * @package Aternos\IO\Interfaces\Features
 */
interface ChangeNameInterface extends GetNameInterface
{
    /**
     * Change the name
     *
     * @param string $name
     * @return $this
     */
    public function changeName(string $name): static;
}