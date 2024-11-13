<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Exception\IOException;

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
     * @throws IOException
     * @param string $name
     * @return $this
     */
    public function changeName(string $name): static;
}