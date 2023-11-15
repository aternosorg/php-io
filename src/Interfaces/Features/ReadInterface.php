<?php

namespace Aternos\IO\Interfaces\Features;

interface ReadInterface extends GetPositionInterface
{
    /**
     * @param int $length
     * @return string
     */
    public function read(int $length): string;
}