<?php

namespace Aternos\IO\Test\Unit\System\Util;

use Aternos\IO\System\Util\PermissionsClass;
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
}