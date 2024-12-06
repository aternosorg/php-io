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
    protected const int READ_MASK = 0b100;
    protected const int WRITE_MASK = 0b010;
    protected const int EXECUTE_MASK = 0b001;

    /**
     * @param int $permissions
     * @return static
     */
    public static function fromNumeric(int $permissions): static
    {
        return new static(
            ($permissions & static::READ_MASK) === static::READ_MASK,
            ($permissions & static::WRITE_MASK) === static::WRITE_MASK,
            ($permissions & static::EXECUTE_MASK) === static::EXECUTE_MASK
        );
    }

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

    /**
     * @inheritDoc
     */
    public function toNumeric(): int
    {
        return ($this->read ? static::READ_MASK : 0)
            + ($this->write ? static::WRITE_MASK : 0)
            + ($this->execute ? static::EXECUTE_MASK : 0);
    }
}