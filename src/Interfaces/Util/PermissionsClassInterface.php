<?php

namespace Aternos\IO\Interfaces\Util;

/**
 * Interface PermissionsClassInterface
 *
 * Interface for class permissions
 *
 * @package Aternos\IO\Interfaces\Util
 */
interface PermissionsClassInterface
{
    /**
     * Check if the class has read permissions
     *
     * @return bool
     */
    public function canRead(): bool;

    /**
     * Set the read permission of the class
     *
     * @param bool $read
     * @return $this
     */
    public function setRead(bool $read): static;

    /**
     * Check if the class has write permissions
     *
     * @return bool
     */
    public function canWrite(): bool;

    /**
     * Set the write permission of the class
     *
     * @param bool $write
     * @return $this
     */
    public function setWrite(bool $write): static;

    /**
     * Check if the class has execute permissions
     *
     * @return bool
     */
    public function canExecute(): bool;

    /**
     * Set the execute permission of the class
     *
     * @param bool $execute
     * @return $this
     */
    public function setExecute(bool $execute): static;
}