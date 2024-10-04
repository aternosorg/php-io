<?php

namespace Aternos\IO\Test\Unit\Abstract\Buffer;

use Aternos\IO\Abstract\Buffer\Buffer;
use PHPUnit\Framework\TestCase;

class BufferTest extends TestCase
{
    public function testGetAndSetAbsoluteStartPosition(): void
    {
        $buffer = new Buffer();
        $buffer->setAbsoluteStartPosition(10);
        $this->assertEquals(10, $buffer->getAbsoluteStartPosition());
    }

    public function testGetAndSetBuffer(): void
    {
        $buffer = new Buffer();
        $buffer->setBuffer("test");
        $this->assertEquals("test", $buffer->getBuffer());
    }

    public function testGetAndSetRelativeBufferPosition(): void
    {
        $buffer = new Buffer();
        $buffer->setRelativeBufferPosition(5);
        $this->assertEquals(5, $buffer->getRelativeBufferPosition());
    }

    public function testGetPosition(): void
    {
        $buffer = new Buffer(10, "test", 5);
        $this->assertEquals(15, $buffer->getPosition());
    }

    public function testSetPosition(): void
    {
        $buffer = new Buffer(10, "test", 5);
        $buffer->setPosition(20);
        $this->assertEquals(10, $buffer->getRelativeBufferPosition());
    }

    public function testIsInBuffer(): void
    {
        $buffer = new Buffer(10, "test", 0);
        $this->assertTrue($buffer->isInBuffer(12));
        $this->assertFalse($buffer->isInBuffer(5));
    }

    public function testRead(): void
    {
        $buffer = new Buffer(0, "test", 0);
        $this->assertEquals("te", $buffer->read(2));
        $this->assertEquals(2, $buffer->getRelativeBufferPosition());

        $this->assertEquals("st", $buffer->read(2));
        $this->assertEquals(4, $buffer->getRelativeBufferPosition());
    }
}