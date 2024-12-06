<?php

namespace Aternos\IO\System\Util;

use Aternos\IO\Interfaces\Util\PermissionsClassInterface;
use Aternos\IO\Interfaces\Util\PermissionsInterface;

/**
 * Class Permissions
 *
 * Holds permission classes for an element
 *
 * @package Aternos\IO\System\Util
 */
class Permissions implements PermissionsInterface
{
    protected const int CLASS_MASK = 0b111;
    protected const int READ_SHIFT = 6;
    protected const int WRITE_SHIFT = 3;
    protected const int EXECUTE_SHIFT = 0;

    /**
     * @param int $permissions
     * @return static
     */
    public static function fromNumeric(int $permissions): static
    {
        return new static(
            PermissionsClass::fromNumeric(
                ($permissions >> static::READ_SHIFT) & static::CLASS_MASK
            ),
            PermissionsClass::fromNumeric(
                ($permissions >> static::WRITE_SHIFT) & static::CLASS_MASK
            ),
            PermissionsClass::fromNumeric(
                ($permissions >> static::EXECUTE_SHIFT) & static::CLASS_MASK
            )
        );
    }


    /**
     * @param PermissionsClassInterface $user
     * @param PermissionsClassInterface $group
     * @param PermissionsClassInterface $other
     */
    public function __construct(
        protected PermissionsClassInterface $user = new PermissionsClass(),
        protected PermissionsClassInterface $group = new PermissionsClass(),
        protected PermissionsClassInterface $other = new PermissionsClass()
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getUser(): PermissionsClassInterface
    {
        return $this->user;
    }

    /**
     * @inheritDoc
     */
    public function getGroup(): PermissionsClassInterface
    {
        return $this->group;
    }

    /**
     * @inheritDoc
     */
    public function getOther(): PermissionsClassInterface
    {
        return $this->other;
    }

    /**
     * @inheritDoc
     */
    public function setUser(PermissionsClassInterface $user): static
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setGroup(PermissionsClassInterface $group): static
    {
        $this->group = $group;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setOther(PermissionsClassInterface $other): static
    {
        $this->other = $other;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toNumeric(): int
    {
        return ($this->user->toNumeric() << static::READ_SHIFT)
            + ($this->group->toNumeric() << static::WRITE_SHIFT)
            + ($this->other->toNumeric() << static::EXECUTE_SHIFT);
    }
}