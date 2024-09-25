<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Interfaces\IOElementInterface;

interface GetSizeInterface extends IOElementInterface
{
    /**
     * @return int
     */
    public function getSize(): int;
}