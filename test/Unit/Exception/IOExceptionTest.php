<?php

namespace Aternos\IO\Test\Unit\Exception;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Filesystem\ReadWriteFile;
use PHPUnit\Framework\TestCase;

class IOExceptionTest extends TestCase
{
    public function testGetMessage(): void
    {
        $exception = new IOException("test");
        $this->assertSame("test", $exception->getMessage());
    }

    public function testGetElement(): void
    {
        $element = new ReadWriteFile("test");
        $exception = new IOException("test", $element);
        $this->assertSame($element, $exception->getIOElement());
    }
}