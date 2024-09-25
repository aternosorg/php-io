<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Interfaces\IOElementInterface;

interface GetTargetPathInterface extends IOElementInterface
{
    /**
     * @return string
     */
    public function getTargetPath(): string;

    /**
     * @return string
     */
    public function getFinalTargetPath(): string;
}