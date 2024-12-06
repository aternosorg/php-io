<?php

namespace Aternos\IO\Test\Unit\System\Util;

use Aternos\IO\System\Util\Permissions;
use Aternos\IO\System\Util\PermissionsClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class PermissionsTest extends TestCase
{
    public function testConstructDefaults(): void
    {
        $permissions = new Permissions();
        $this->assertFalse($permissions->getUser()->canRead());
        $this->assertFalse($permissions->getUser()->canWrite());
        $this->assertFalse($permissions->getUser()->canExecute());
        $this->assertFalse($permissions->getGroup()->canRead());
        $this->assertFalse($permissions->getGroup()->canWrite());
        $this->assertFalse($permissions->getGroup()->canExecute());
        $this->assertFalse($permissions->getOther()->canRead());
        $this->assertFalse($permissions->getOther()->canWrite());
        $this->assertFalse($permissions->getOther()->canExecute());
    }

    public function testGetPermissionClasses(): void
    {
        $userPermissions = new PermissionsClass(true, false, true);
        $groupPermissions = new PermissionsClass(false, true, false);
        $otherPermissions = new PermissionsClass(true, true, true);

        $permissions = new Permissions($userPermissions, $groupPermissions, $otherPermissions);
        $this->assertSame($userPermissions, $permissions->getUser());
        $this->assertSame($groupPermissions, $permissions->getGroup());
        $this->assertSame($otherPermissions, $permissions->getOther());
    }

    public function testSetUser(): void
    {
        $permissions = new Permissions();
        $this->assertFalse($permissions->getUser()->canRead());
        $this->assertFalse($permissions->getUser()->canWrite());
        $this->assertFalse($permissions->getUser()->canExecute());
        $userPermissions = new PermissionsClass(true, false, true);
        $permissions->setUser($userPermissions);
        $this->assertSame($userPermissions, $permissions->getUser());
    }

    public function testSetGroup(): void
    {
        $permissions = new Permissions();
        $this->assertFalse($permissions->getGroup()->canRead());
        $this->assertFalse($permissions->getGroup()->canWrite());
        $this->assertFalse($permissions->getGroup()->canExecute());
        $groupPermissions = new PermissionsClass(true, false, true);
        $permissions->setGroup($groupPermissions);
        $this->assertSame($groupPermissions, $permissions->getGroup());
    }

    public function testSetOther(): void
    {
        $permissions = new Permissions();
        $this->assertFalse($permissions->getOther()->canRead());
        $this->assertFalse($permissions->getOther()->canWrite());
        $this->assertFalse($permissions->getOther()->canExecute());
        $otherPermissions = new PermissionsClass(true, false, true);
        $permissions->setOther($otherPermissions);
        $this->assertSame($otherPermissions, $permissions->getOther());
    }

    #[DataProvider("getTestData")]
    public function testFromNumeric(int $numericPermissions, $userRead, $userWrite, $userExecute, $groupRead, $groupWrite, $groupExecute, $otherRead, $otherWrite, $otherExecute): void
    {
        $permissions = Permissions::fromNumeric($numericPermissions);
        $this->assertSame($userRead, $permissions->getUser()->canRead());
        $this->assertSame($userWrite, $permissions->getUser()->canWrite());
        $this->assertSame($userExecute, $permissions->getUser()->canExecute());
        $this->assertSame($groupRead, $permissions->getGroup()->canRead());
        $this->assertSame($groupWrite, $permissions->getGroup()->canWrite());
        $this->assertSame($groupExecute, $permissions->getGroup()->canExecute());
        $this->assertSame($otherRead, $permissions->getOther()->canRead());
        $this->assertSame($otherWrite, $permissions->getOther()->canWrite());
        $this->assertSame($otherExecute, $permissions->getOther()->canExecute());
    }

    #[DataProvider("getTestData")]
    public function testToNumeric(int $numericPermissions, $userRead, $userWrite, $userExecute, $groupRead, $groupWrite, $groupExecute, $otherRead, $otherWrite, $otherExecute): void
    {
        $permissions = new Permissions(
            new PermissionsClass($userRead, $userWrite, $userExecute),
            new PermissionsClass($groupRead, $groupWrite, $groupExecute),
            new PermissionsClass($otherRead, $otherWrite, $otherExecute)
        );
        $this->assertSame($numericPermissions, $permissions->toNumeric());
    }

    public static function getTestData(): array
    {
        return [
            [0o000, false, false, false, false, false, false, false, false, false],
            [0o100, false, false, true, false, false, false, false, false, false],
            [0o200, false, true, false, false, false, false, false, false, false],
            [0o400, true, false, false, false, false, false, false, false, false],
            [0o010, false, false, false, false, false, true, false, false, false],
            [0o020, false, false, false, false, true, false, false, false, false],
            [0o040, false, false, false, true, false, false, false, false, false],
            [0o001, false, false, false, false, false, false, false, false, true],
            [0o002, false, false, false, false, false, false, false, true, false],
            [0o004, false, false, false, false, false, false, true, false, false],
            [0o777, true, true, true, true, true, true, true, true, true],
            [0o755, true, true, true, true, false, true, true, false, true],
            [0o070, false, false, false, true, true, true, false, false, false]
        ];
    }
}