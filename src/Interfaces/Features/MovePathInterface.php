<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Exception\IOException;

/**
 * Interface MovePathInterface
 *
 * Allows moving a path
 *
 * @package Aternos\IO\Interfaces\Features
 */
interface MovePathInterface extends GetPathInterface, ChangeNameInterface
{
    /**
     * Move the element to the given path
     *
     * @throws IOException
     * @param string $path
     * @return $this
     */
    public function move(string $path): static;
}