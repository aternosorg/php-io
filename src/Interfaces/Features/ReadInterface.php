<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Exception\IOException;

/**
 * Interface ReadInterface
 *
 * Allows reading from the element
 *
 * @package Aternos\IO\Interfaces\Features
 */
interface ReadInterface extends GetPositionInterface
{
    /**
     * Read $length bytes from the element
     *
     * @throws IOException
     * @param int $length
     * @return string
     */
    public function read(int $length): string;
}