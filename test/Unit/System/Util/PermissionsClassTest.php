<?php

namespace Aternos\IO\Test\Unit\System\Util;

use Aternos\IO\System\Util\PermissionsClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class PermissionsClassTest extends TestCase
{
    public function testGetPermissions(): void
    {
        $permissions = new PermissionsClass(true, false, true);
        $this->assertTrue($permissions->canRead());
        $this->assertFalse($permissions->canWrite());
        $this->assertTrue($permissions->canExecute());
    }

    public function testSetRead(): void
    {
        $permissions = new PermissionsClass();
        $this->assertFalse($permissions->canRead());
        $permissions->setRead(true);
        $this->assertTrue($permissions->canRead());
    }

    public function testSetWrite(): void
    {
        $permissions = new PermissionsClass();
        $this->assertFalse($permissions->canWrite());
        $permissions->setWrite(true);
        $this->assertTrue($permissions->canWrite());
    }

    public function testSetExecute(): void
    {
        $permissions = new PermissionsClass();
        $this->assertFalse($permissions->canExecute());
        $permissions->setExecute(true);
        $this->assertTrue($permissions->canExecute());
    }

    #[DataProvider("getTestData")]
    public function testFromNumeric(int $numericClass, bool $read, bool $write, bool $execute): void
    {
        $permissions = PermissionsClass::fromNumeric($numericClass);
        $this->assertSame($read, $permissions->canRead());
        $this->assertSame($write, $permissions->canWrite());
        $this->assertSame($execute, $permissions->canExecute());
    }

    #[DataProvider("getTestData")]
    public function testToNumeric(int $numericClass, bool $read, bool $write, bool $execute): void
    {
        $permissions = new PermissionsClass($read, $write, $execute);
        $this->assertSame($numericClass, $permissions->toNumeric());
    }

    public static function getTestData(): array
    {
        return [
            [0, false, false, false],
            [1, false, false, true],
            [2, false, true, false],
            [3, false, true, true],
            [4, true, false, false],
            [5, true, false, true],
            [6, true, true, false],
            [7, true, true, true]
        ];
    }
}