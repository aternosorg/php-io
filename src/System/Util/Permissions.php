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
    /**
     * @param int $permissions
     * @return static
     */
    public static function fromNumeric(int $permissions): static
    {
        return new static(
            new PermissionsClass(
                ($permissions & 0o100) === 0o100,
                ($permissions & 0o200) === 0o200,
                ($permissions & 0o400) === 0o400
            ),
            new PermissionsClass(
                ($permissions & 0o010) === 0o010,
                ($permissions & 0o020) === 0o020,
                ($permissions & 0o040) === 0o040
            ),
            new PermissionsClass(
                ($permissions & 0o001) === 0o001,
                ($permissions & 0o002) === 0o002,
                ($permissions & 0o004) === 0o004
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
}