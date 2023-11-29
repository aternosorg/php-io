<?php

namespace Aternos\IO\Test\Unit\System\File;

use Aternos\IO\Exception\ReadException;
use Aternos\IO\Exception\SeekException;
use Aternos\IO\Exception\StatException;
use Aternos\IO\Exception\TruncateException;
use Aternos\IO\Exception\WriteException;
use Aternos\IO\Interfaces\Types\VolatileFileInterface;
use ReflectionException;
use ReflectionObject;

trait VolatileFileTestTrait
{
    abstract protected function getVolatileFile(): VolatileFileInterface;

    public function testReadWrite(): void
    {
        $file = $this->getVolatileFile();
        $file->write('test');
        $file->setPosition(0);
        $this->assertEquals('test', $file->read(4));
    }

    public function testWriteEmpty(): void
    {
        $file = $this->getVolatileFile();
        $file->write('');
        $this->assertEquals('', $file->read(4));
    }

    /**
     * @throws ReflectionException
     */
    public function testThrowsExceptionOnWrite(): void
    {
        $file = $this->getVolatileFile();
        $reflectionFile = new ReflectionObject($file);
        $socketResource = $reflectionFile->getProperty('socketResource');
        $socketResource->setValue($file, fopen('php://input', 'r'));
        $this->expectException(WriteException::class);
        $file->write('test');
    }

    public function testReadEmpty(): void
    {
        $file = $this->getVolatileFile();
        $this->assertEquals('', $file->read(0));
        $file->write('test');
        $file->setPosition(0);
        $this->assertEquals('', $file->read(0));
    }

    /**
     * @throws ReflectionException
     */
    public function testThrowsExceptionOnRead(): void
    {
        $file = $this->getVolatileFile();
        $reflectionFile = new ReflectionObject($file);
        $socketResource = $reflectionFile->getProperty('socketResource');
        $socketResource->setValue($file, fopen('php://output', 'w'));
        $this->expectException(ReadException::class);
        $file->read(4);
    }

    public function testGetSetPosition(): void
    {
        $file = $this->getVolatileFile();
        $this->assertEquals(0, $file->getPosition());
        $file->write('test');
        $this->assertEquals(4, $file->getPosition());
        $file->setPosition(2);
        $this->assertEquals(2, $file->getPosition());
    }

    public function testSetPositionThrowsException(): void
    {
        $file = $this->getVolatileFile();
        $this->expectException(SeekException::class);
        $file->setPosition(-1);
    }

    public function testCheckEndOfFile(): void
    {
        $file = $this->getVolatileFile();
        $file->write('test');
        $this->assertFalse($file->isEndOfFile());
        $file->read(1);
        $this->assertTrue($file->isEndOfFile());
    }

    /**
     * @throws ReflectionException
     */
    public function testClose(): void
    {
        $file = $this->getVolatileFile();
        $file->write('test');
        $reflectionObject = new ReflectionObject($file);
        $reflectionProperty = $reflectionObject->getProperty('socketResource');
        $this->assertIsResource($reflectionProperty->getValue($file));
        $file->close();
        $this->assertNull($reflectionProperty->getValue($file));
    }

    public function testGetSize(): void
    {
        $file = $this->getVolatileFile();
        $this->assertEquals(0, $file->getSize());
        $file->write('test');
        $this->assertEquals(4, $file->getSize());
    }

    /**
     * @throws ReflectionException
     */
    public function testThrowsExceptionOnGetSize(): void
    {
        $file = $this->getVolatileFile();
        $reflectionFile = new ReflectionObject($file);
        $socketResource = $reflectionFile->getProperty('socketResource');
        $socketResource->setValue($file, fopen('php://input', 'r'));
        $this->expectException(StatException::class);
        $file->getSize();
    }

    public function testTruncate(): void
    {
        $file = $this->getVolatileFile();
        $file->write('test');
        $file->truncate(2);
        $this->assertEquals(2, $file->getSize());
        $file->setPosition(0);
        $this->assertEquals('te', $file->read(2));
    }

    public function testThrowsExceptionOnTruncate(): void
    {
        $file = $this->getVolatileFile();
        $reflectionFile = new ReflectionObject($file);
        $socketResource = $reflectionFile->getProperty('socketResource');
        $socketResource->setValue($file, fopen('php://input', 'r'));
        $this->expectException(TruncateException::class);
        $file->truncate(2);
    }
}