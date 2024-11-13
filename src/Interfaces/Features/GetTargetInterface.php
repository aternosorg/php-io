<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Interfaces\IOElementInterface;

/**
 * Interface GetTargetInterface
 *
 * Allows getting the target of a link
 *
 * @package Aternos\IO\Interfaces\Features
 */
interface GetTargetInterface extends IOElementInterface
{
    /**
     * Get the target of the link
     *
     * @throws IOException
     * @return IOElementInterface
     */
    public function getTarget(): IOElementInterface;

    /**
     * Get the final target of the link, following all links until the final target is reached
     *
     * @throws IOException
     * @return IOElementInterface
     */
    public function getFinalTarget(): IOElementInterface;
}