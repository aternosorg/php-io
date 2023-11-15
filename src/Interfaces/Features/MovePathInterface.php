<?php

namespace Aternos\IO\Interfaces\Features;

interface MovePathInterface extends GetPathInterface, ChangeNameInterface
{
    /**
     * @param string $path
     * @return $this
     */
    public function move(string $path): static;
}