<?php

namespace Aternos\IO\Test\Unit\System\File;

use Aternos\IO\Exception\DeleteException;
use Aternos\IO\Exception\IOException;
use Aternos\IO\Exception\ReadException;
use Aternos\IO\Exception\SeekException;
use Aternos\IO\Exception\WriteException;
use Aternos\IO\System\File\TempDiskFile;
use PHPUnit\Framework\TestCase;

class TempDiskFileTest extends TestCase
{
    public function testSelectsPathForTemporaryFile(): void
    {
        $file = new TempDiskFile();
        $this->assertIsString($file->getPath());
        $this->assertFileExists($file->getPath());
    }

    public function testSelectsPathWithPrefix(): void
    {
        $file = new TempDiskFile("test-");
        $this->assertStringStartsWith("test-", $file->getName());
    }

    public function testDeletesFileOnDestruct(): void
    {
        $file = new TempDiskFile();
        $path = $file->getPath();
        $this->assertFileExists($path);
        unset($file);
        $this->assertFileDoesNotExist($path);
    }

    public function testDoesNotDeleteFileOnDestruct(): void
    {
        $file = new TempDiskFile("test-", false);
        $path = $file->getPath();
        $this->assertFileExists($path);
        unset($file);
        $this->assertFileExists($path);
        unlink($path);
    }

    /**
     * @throws IOException
     * @throws DeleteException
     */
    public function testSerializeContainsDeleteOnDestruct(): void
    {
        $file = new TempDiskFile("test-", false);
        $serialized = $file->__serialize();
        $this->assertArrayHasKey("deleteOnDestruct", $serialized);
        $file->delete();
    }

    /**
     * @throws IOException
     * @throws ReadException
     * @throws WriteException
     * @throws SeekException
     */
    public function testCopyTo(): void
    {
        $file = new TempDiskFile();
        $file->write("test");
        $file->setPosition(0);
        $target = new TempDiskFile();
        $file->copyTo($target);
        $this->assertEquals("test", file_get_contents($target->getPath()));
        $file->copyTo($target);
        $file->copyTo($target);
        /** @noinspection SpellCheckingInspection */
        $this->assertEquals("testtesttest", file_get_contents($target->getPath()));
    }
}