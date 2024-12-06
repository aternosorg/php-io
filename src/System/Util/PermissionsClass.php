<?php

namespace Aternos\IO\System\Util;

use Aternos\IO\Interfaces\Util\PermissionsClassInterface;

/**
 * Class PermissionsClass
 *
 * Holds permission values for a specific class
 *
 * @package Aternos\IO\System\Util
 */
class PermissionsClass implements PermissionsClassInterface
{
    /**
     * @param bool $read
     * @param bool $write
     * @param bool $execute
     */
    public function __construct(
        protected bool $read = false,
        protected bool $write = false,
        protected bool $execute = false
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function canRead(): bool
    {
        return $this->read;
    }

    /**
     * @inheritDoc
     */
    public function canWrite(): bool
    {
        return $this->write;
    }

    /**
     * @inheritDoc
     */
    public function canExecute(): bool
    {
        return $this->execute;
    }

    /**
     * @inheritDoc
     */
    public function setRead(bool $read): static
    {
        $this->read = $read;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setWrite(bool $write): static
    {
        $this->write = $write;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setExecute(bool $execute): static
    {
        $this->execute = $execute;
        return $this;
    }
}