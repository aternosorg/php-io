<?php

namespace Aternos\IO\Interfaces\Features;

interface GetTargetPathInterface
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