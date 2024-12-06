<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Interfaces\IOElementInterface;
use Aternos\IO\Interfaces\Util\PermissionsInterface;

/**
 * Interface SetPermissionsInterface
 *
 * Allows setting the permissions of an element
 *
 * @package Aternos\IO\Interfaces\Features
 */
interface SetPermissionsInterface extends IOElementInterface
{
    /**
     * Set the permissions of this element
     *
     * @throws IOException
     * @param PermissionsInterface $permissions
     * @return $this
     */
    public function setPermissions(PermissionsInterface $permissions): static;
}