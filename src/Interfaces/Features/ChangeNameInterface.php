<?php

namespace Aternos\IO\Interfaces\Features;

interface ChangeNameInterface extends GetNameInterface
{
    /**
     * @param string $name
     * @return $this
     */
    public function changeName(string $name): static;
}