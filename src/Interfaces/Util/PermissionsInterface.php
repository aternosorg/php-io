<?php

namespace Aternos\IO\Interfaces\Util;

/**
 * Interface PermissionsInterface
 *
 * Interface for element permissions by class
 *
 * @package Aternos\IO\Interfaces\Util
 */
interface PermissionsInterface
{
    /**
     * Get the user permissions
     *
     * @return PermissionsClassInterface
     */
    public function getUser(): PermissionsClassInterface;

    /**
     * Set the user permissions
     *
     * @param PermissionsClassInterface $user
     * @return $this
     */
    public function setUser(PermissionsClassInterface $user): static;

    /**
     * Get the group permissions
     *
     * @return PermissionsClassInterface
     */
    public function getGroup(): PermissionsClassInterface;

    /**
     * Set the group permissions
     *
     * @param PermissionsClassInterface $group
     * @return $this
     */
    public function setGroup(PermissionsClassInterface $group): static;

    /**
     * Get the other permissions
     *
     * @return PermissionsClassInterface
     */
    public function getOther(): PermissionsClassInterface;

    /**
     * Set the other permissions
     *
     * @param PermissionsClassInterface $other
     * @return $this
     */
    public function setOther(PermissionsClassInterface $other): static;

    /**
     * Get the numeric representation of the permissions
     *
     * @return int
     */
    public function toNumeric(): int;
}