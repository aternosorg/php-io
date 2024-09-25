<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Interfaces\IOElementInterface;

interface GetPositionInterface extends IOElementInterface
{
    /**
     * @return int
     */
    public function getPosition(): int;
}