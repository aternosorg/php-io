<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Interfaces\IOElementInterface;

interface GetNameInterface extends IOElementInterface
{
    /**
     * @return string
     */
    public function getName(): string;
}