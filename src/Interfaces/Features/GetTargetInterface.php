<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Interfaces\IOElementInterface;

interface GetTargetInterface extends IOElementInterface
{
    /**
     * @return IOElementInterface
     */
    public function getTarget(): IOElementInterface;

    /**
     * @return IOElementInterface
     */
    public function getFinalTarget(): IOElementInterface;
}