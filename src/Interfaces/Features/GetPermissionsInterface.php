<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Interfaces\IOElementInterface;
use Aternos\IO\Interfaces\Util\PermissionsInterface;

/**
 * Interface GetPermissionsInterface
 *
 * Allows getting the permissions of an element
 *
 * @package Aternos\IO\Interfaces\Features
 */
interface GetPermissionsInterface extends IOElementInterface
{
    /**
     * Get the permissions of this element
     *
     * @throws IOException
     * @return PermissionsInterface
     */
    public function getPermissions(): PermissionsInterface;
}