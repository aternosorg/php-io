<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Interfaces\IOElementInterface;

/**
 * Interface CreateInterface
 *
 * Allows creating an element
 *
 * @package Aternos\IO\Interfaces\Features
 */
interface CreateInterface extends IOElementInterface
{
    /**
     * Create the element
     *
     * @throws IOException
     * @return $this
     */
    public function create(): static;
}