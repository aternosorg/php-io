<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Interfaces\IOElementInterface;

/**
 * Interface TargetExistsInterface
 *
 * Allows checking if a link target exists
 *
 * @package Aternos\IO\Interfaces\Features
 */
interface TargetExistsInterface extends IOElementInterface
{
    /**
     * Check if the target of the link exists
     *
     * @throws IOException
     * @return bool
     */
    public function targetExists(): bool;
}