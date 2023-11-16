<?php

namespace Aternos\IO\Test\Unit\Filesystem;

use Aternos\IO\Exception\MissingPermissionsException;
use Aternos\IO\Filesystem\ReadDirectory;
use Aternos\IO\Interfaces\Types\File\ReadFileInterface;

class ReadDirectoryTest extends DirectoryTest
{
    protected string $directoryClass = ReadDirectory::class;
    protected string $fileClass = ReadFileInterface::class;

    protected function createElement(string $path): ReadDirectory
    {
        return new ReadDirectory($path);
    }

    /**
     * @return void
     * @throws MissingPermissionsException
     */
    protected function testThrowsNoExceptionOnReadingReadOnlyFile(): void
    {
        $path = $this->getTmpPath();
        $directory = $this->createElement($path);
        file_put_contents($path . "/test", "test");
        chmod($path . "/test", 0444);
        foreach ($directory->getChildren() as $child) {
            $this->assertInstanceOf(ReadFileInterface::class, $child);
            $this->assertEquals("test", $child->read(4));
        }
    }
}