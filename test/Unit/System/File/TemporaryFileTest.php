<?php

namespace Aternos\IO\Test\Unit\System\File;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Exception\ReadException;
use Aternos\IO\Exception\SeekException;
use Aternos\IO\Exception\WriteException;
use Aternos\IO\System\File\TemporaryFile;
use PHPUnit\Framework\TestCase;

class TemporaryFileTest extends TestCase
{
    public function testSelectsPathForTemporaryFile(): void
    {
        $file = new TemporaryFile();
        $this->assertIsString($file->getPath());
        $this->assertFileExists($file->getPath());
    }

    public function testSelectsPathWithPrefix(): void
    {
        $file = new TemporaryFile("test-");
        $this->assertStringStartsWith("test-", $file->getName());
    }

    public function testDeletesFileOnDestruct(): void
    {
        $file = new TemporaryFile();
        $path = $file->getPath();
        $this->assertFileExists($path);
        unset($file);
        $this->assertFileDoesNotExist($path);
    }

    public function testDoesNotDeleteFileOnDestruct(): void
    {
        $file = new TemporaryFile("test-", false);
        $path = $file->getPath();
        $this->assertFileExists($path);
        unset($file);
        $this->assertFileExists($path);
    }

    public function testSerializeContainsDeleteOnDestruct(): void
    {
        $file = new TemporaryFile("test-", false);
        $serialized = $file->__serialize();
        $this->assertArrayHasKey("deleteOnDestruct", $serialized);
    }

    /**
     * @throws IOException
     * @throws ReadException
     * @throws WriteException
     * @throws SeekException
     */
    public function testCopyTo(): void
    {
        $file = new TemporaryFile();
        $file->write("test");
        $file->setPosition(0);
        $target = new TemporaryFile();
        $file->copyTo($target);
        $this->assertEquals("test", file_get_contents($target->getPath()));
        $file->copyTo($target);
        $file->copyTo($target);
        $this->assertEquals("testtesttest", file_get_contents($target->getPath()));
    }
}