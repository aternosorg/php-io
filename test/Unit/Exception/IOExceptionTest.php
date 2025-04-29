<?php

namespace Aternos\IO\Test\Unit\Exception;

use Aternos\IO\Exception\IOException;
use Aternos\IO\System\File\File;
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
        $element = new File("test");
        $exception = new IOException("test", $element);
        $this->assertSame($element, $exception->getIOElement());
    }

    public function testGetPrevious(): void
    {
        $previous = new \Exception("previous");
        $exception = new IOException("test", null, $previous);
        $this->assertSame($previous, $exception->getPrevious());
    }
}
