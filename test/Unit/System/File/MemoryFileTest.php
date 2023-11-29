<?php

namespace Aternos\IO\Test\Unit\System\File;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Interfaces\Types\VolatileFileInterface;
use Aternos\IO\System\File\MemoryFile;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionObject;

class MemoryFileTest extends TestCase
{
    use VolatileFileTestTrait;

    public function testGetName(): void
    {
        $memoryFile = new MemoryFile();
        $this->assertEquals('memory', $memoryFile->getName());
    }

    protected function getVolatileFile(): VolatileFileInterface
    {
        return new MemoryFile();
    }

    /**
     * @throws ReflectionException
     */
    public function testThrowsExceptionOnOpen(): void
    {
        $file = $this->getVolatileFile();
        $fileReflection = new ReflectionObject($file);
        $fileReflection->getProperty('address')->setValue($file, 'php://test');

        $this->expectException(IOException::class);
        $file->write('test');
    }
}
