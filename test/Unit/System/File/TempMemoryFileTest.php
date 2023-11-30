<?php

namespace Aternos\IO\Test\Unit\System\File;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Interfaces\Types\VolatileFileInterface;
use Aternos\IO\System\File\TempMemoryFile;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionObject;

class TempMemoryFileTest extends TestCase
{
    use VolatileFileTestTrait;

    public function testGetName(): void
    {
        $memoryFile = new TempMemoryFile();
        $this->assertEquals('memory', $memoryFile->getName());
    }

    protected function getVolatileFile(): VolatileFileInterface
    {
        return new TempMemoryFile();
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
