<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Interfaces\IOElementInterface;

/**
 * Interface ExistsInterface
 *
 * Allows checking if an element exists
 *
 * @package Aternos\IO\Interfaces\Features
 */
interface ExistsInterface extends IOElementInterface
{
    /**
     * Check if the element exists
     *
     * @throws IOException
     * @return bool
     */
    public function exists(): bool;
}