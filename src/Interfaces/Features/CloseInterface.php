<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Interfaces\IOElementInterface;

/**
 * Interface CloseInterface
 *
 * Allows closing an element, e.g. a file resource or a stream
 *
 * @package Aternos\IO\Interfaces\Features
 */
interface CloseInterface extends IOElementInterface
{
    /**
     * Close the element, e.g. a file resource or a stream
     *
     * @throws IOException
     * @return $this
     */
    public function close(): static;
}