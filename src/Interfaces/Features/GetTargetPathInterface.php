<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Interfaces\IOElementInterface;

/**
 * Interface GetTargetPathInterface
 *
 * Allows getting the target path of a link element
 *
 * @package Aternos\IO\Interfaces\Features
 */
interface GetTargetPathInterface extends IOElementInterface
{
    /**
     * Get the target path of the link element
     *
     * @throws IOException
     * @return string
     */
    public function getTargetPath(): string;

    /**
     * Get the final target path of the link element, following all links until the final target is reached
     *
     * @throws IOException
     * @return string
     */
    public function getFinalTargetPath(): string;
}