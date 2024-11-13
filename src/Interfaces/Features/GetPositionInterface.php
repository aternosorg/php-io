<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Interfaces\IOElementInterface;

/**
 * Interface GetPositionInterface
 *
 * Allows getting the current seek position of an element
 *
 * @package Aternos\IO\Interfaces\Features
 */
interface GetPositionInterface extends IOElementInterface
{
    /**
     * Get the current seek position
     *
     * @throws IOException
     * @return int
     */
    public function getPosition(): int;
}