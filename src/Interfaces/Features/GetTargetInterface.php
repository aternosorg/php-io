<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Interfaces\IOElementInterface;

interface GetTargetInterface
{
    /**
     * @return IOElementInterface
     */
    public function getTarget(): IOElementInterface;
}