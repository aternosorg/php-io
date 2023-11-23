<?php

namespace Aternos\IO\Test\Unit\Filesystem\File;

use Aternos\IO\Exception\CreateDirectoryException;
use Aternos\IO\Exception\CreateFileException;
use Aternos\IO\Exception\DeleteException;
use Aternos\IO\Exception\IOException;
use Aternos\IO\Exception\MissingPermissionsException;
use Aternos\IO\Exception\ReadException;
use Aternos\IO\Exception\SeekException;
use Aternos\IO\Exception\StatException;
use Aternos\IO\Exception\TruncateException;
use Aternos\IO\Exception\WriteException;
use Aternos\IO\Filesystem\File\File;
use Aternos\IO\Test\Unit\Filesystem\FilesystemTestCase;
use ReflectionObject;

class FileTest extends FilesystemTestCase
{
    protected function createElement(string $path): File
    {
        return new File($path);
    }

    /**
     * @throws StatException
     * @throws IOException
     */
    public function testGetSize(): void
    {
        $path = $this->getTmpPath() . "/test";
        file_put_contents($path, "test");
        $element = $this->createElement($path);
        $this->assertEquals(4, $element->getSize());
    }

    /**
     * @throws IOException
     */
    public function testThrowsExceptionOnImpossibleGetSize(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $this->expectException(StatException::class);
        $this->expectExceptionMessage("Could not get file size (" . $path . ")");
        $element->getSize();
    }

    /**
     * @throws IOException
     * @throws ReadException
     */
    public function testRead(): void
    {
        $path = $this->getTmpPath() . "/test";
        file_put_contents($path, "test");
        $element = $this->createElement($path);
        $this->assertEquals("test", $element->read(4));
    }

    /**
     * @throws IOException
     * @throws ReadException
     */
    public function testReadNothing(): void
    {
        $path = $this->getTmpPath() . "/test";
        file_put_contents($path, "test");
        $element = $this->createElement($path);
        $this->assertEquals("", $element->read(0));
    }

    /**
     * @throws IOException
     */
    public function testThrowsExceptionOnImpossibleRead(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        file_put_contents($path, "test");
        $reflectionObject = new ReflectionObject($element);
        $reflectionObject->getProperty("fileResource")->setValue($element, fopen($path, "w"));
        $this->expectException(ReadException::class);
        $this->expectExceptionMessage("Could not read file (" . $path . ")");
        $element->read(4);
    }

    /**
     * @throws ReadException
     */
    public function testThrowsExceptionOnInvalidPath(): void
    {
        $element = $this->createElement("/dev/null/test");
        $this->expectException(IOException::class);
        $this->expectExceptionMessage("Could not open file (/dev/null/test)");
        $element->read(4);
    }

    /**
     * @throws ReadException
     * @throws IOException
     */
    public function testThrowsExceptionOnMissingReadPermissions(): void
    {
        $path = $this->getTmpPath() . "/test";
        file_put_contents($path, "test");
        chmod($path, 0222);
        $element = $this->createElement($path);
        $this->expectException(MissingPermissionsException::class);
        $this->expectExceptionMessage("Could not read file due to missing read permissions (" . $path . ")");
        $element->read(4);
    }

    /**
     * @throws IOException
     * @throws SeekException
     * @throws ReadException
     */
    public function testSetPosition(): void
    {
        $path = $this->getTmpPath() . "/test";
        file_put_contents($path, "0123456789");
        $element = $this->createElement($path);
        $element->setPosition(5);
        $this->assertEquals("5", $element->read(1));
    }

    /**
     * @throws IOException
     */
    public function testThrowsExceptionOnImpossibleSetPosition(): void
    {
        $path = $this->getTmpPath() . "/test";
        file_put_contents($path, "0123456789");
        $element = $this->createElement($path);
        $this->expectException(SeekException::class);
        $this->expectExceptionMessage("Could not set file position (" . $path . ")");
        $element->setPosition(-1);
    }

    /**
     * @throws IOException
     * @throws ReadException
     * @throws SeekException
     */
    public function testGetPosition(): void
    {
        $path = $this->getTmpPath() . "/test";
        file_put_contents($path, "0123456789");
        $element = $this->createElement($path);
        $element->setPosition(5);
        $this->assertEquals(5, $element->getPosition());
        $this->assertEquals("5", $element->read(1));
        $this->assertEquals(6, $element->getPosition());
    }

    /**
     * @throws IOException
     * @throws ReadException
     */
    public function testClose(): void
    {
        $path = $this->getTmpPath() . "/test";
        file_put_contents($path, "test");
        $element = $this->createElement($path);
        $element->read(4);

        $reflectionObject = new ReflectionObject($element);
        $file = $reflectionObject->getProperty("fileResource")->getValue($element);
        $this->assertIsResource($file);

        $element->close();

        $this->assertIsClosedResource($file);
        $null = $reflectionObject->getProperty("fileResource")->getValue($element);
        $this->assertNull($null);
    }

    /**
     * @return void
     * @throws IOException
     */
    public function testCloseOnDestruct(): void
    {
        $path = $this->getTmpPath() . "/test";
        file_put_contents($path, "test");
        $element = $this->createElement($path);
        $element->read(4);

        $reflectionObject = new ReflectionObject($element);
        $file = $reflectionObject->getProperty("fileResource")->getValue($element);
        $this->assertIsResource($file);

        $element->__destruct();

        $file = $reflectionObject->getProperty("fileResource")->getValue($element);
        $this->assertNull($file);

    }

    /**
     * @throws IOException
     * @throws TruncateException
     */
    public function testTruncate(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        file_put_contents($path, "test");
        $element->truncate(2);
        $this->assertSame("te", file_get_contents($path));
    }

    /**
     * @throws IOException
     */
    public function testThrowsExceptionOnImpossibleTruncate(): void
    {
        $element = $this->createElement("/dev/null");
        $this->expectException(TruncateException::class);
        $this->expectExceptionMessage("Could not truncate file (/dev/null)");
        $element->truncate();
    }

    /**
     * @throws IOException
     * @throws WriteException
     */
    public function testWrite(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $element->write("test");
        $this->assertSame("test", file_get_contents($path));
    }

    /**
     * @throws IOException
     */
    public function testThrowsExceptionOnImpossibleWrite(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        file_put_contents($path, "test");
        $reflectionObject = new ReflectionObject($element);
        $reflectionObject->getProperty("fileResource")->setValue($element, fopen($path, "r"));
        $this->expectException(WriteException::class);
        $this->expectExceptionMessage("Could not write to file (" . $path . ")");
        $element->write("test");
    }

    /**
     * @throws WriteException
     * @throws IOException
     */
    public function testThrowsExceptionOnMissingWritePermissions(): void
    {
        $path = $this->getTmpPath() . "/test";
        file_put_contents($path, "test");
        chmod($path, 0444);
        $element = $this->createElement($path);
        $this->expectException(MissingPermissionsException::class);
        $this->expectExceptionMessage("Could not write to file due to missing write permissions (" . $path . ")");
        $element->write("test");
    }

    /**
     * @throws IOException
     * @throws WriteException
     */
    public function testThrowsExceptionOnMissingWritePermissionsOnParentDirectory(): void
    {
        $path = $this->getTmpPath() . "/test/test";
        mkdir($path, 0777, true);
        chmod($this->getTmpPath() . "/test", 0444);
        $element = $this->createElement($path);
        $this->expectException(MissingPermissionsException::class);
        $this->expectExceptionMessage("Could not open file due to missing write permissions in parent directory (" . $path . ")");
        $element->write("test");
    }

    public function testThrowsExceptionOnImpossibleDelete(): void
    {
        $element = $this->createElement("/dev/null");
        $this->expectException(DeleteException::class);
        $this->expectExceptionMessage("Could not delete file (/dev/null)");
        $element->delete();
    }

    public function testThrowsExceptionOnFailedCreation(): void
    {
        $this->expectException(CreateFileException::class);
        $this->expectExceptionMessage("Could not create file (/dev/null/test)");
        $element = $this->createElement("/dev/null/test");
        $element->create();
    }

    /**
     * @throws ReadException
     * @throws IOException
     */
    public function testThrowsExceptionOnMissingPermissions(): void
    {
        $path = $this->getTmpPath() . "/test";
        file_put_contents($path, "test");
        chmod($path, 0000);
        $element = $this->createElement($path);
        $this->expectException(MissingPermissionsException::class);
        $this->expectExceptionMessage("Could not open file due to missing permissions (" . $path . ")");
        $element->read(4);
    }

    /**
     * @throws IOException
     * @throws WriteException
     */
    public function testSerializeOpenFile(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $element->write("test");
        $serialized = serialize($element);
        $this->assertIsString($serialized);
    }

    /**
     * @throws CreateFileException
     * @throws CreateDirectoryException
     */
    public function testCreate(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $this->assertFalse(file_exists($path));
        $element->create();
        $this->assertTrue(file_exists($path));
    }

    /**
     * @throws CreateDirectoryException
     * @throws CreateFileException
     */
    public function testCreateParentDirectoryOnCreate(): void
    {
        $path = $this->getTmpPath() . "/test/test";
        $element = $this->createElement($path);
        $this->assertFileDoesNotExist($this->getTmpPath() . "/test/test");
        $element->create();
        $this->assertFileExists($this->getTmpPath() . "/test/test");
        $this->assertDirectoryExists($this->getTmpPath() . "/test");
    }

    /**
     * @throws IOException
     * @throws WriteException
     */
    public function testCreateParentDirectoryOnWrite(): void
    {
        $path = $this->getTmpPath() . "/test/test";
        $element = $this->createElement($path);
        $this->assertFileDoesNotExist($this->getTmpPath() . "/test/test");
        $element->write("test");
        $this->assertFileExists($this->getTmpPath() . "/test/test");
        $this->assertDirectoryExists($this->getTmpPath() . "/test");
    }

    public function testSerializeDoesNotContainFileResource(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $serialized = $element->__serialize();
        $this->assertArrayHasKey("path", $serialized);
        $this->assertEquals($path, $serialized["path"]);
        $this->assertArrayNotHasKey("fileResource", $serialized);
    }

    /**
     * @throws ReadException
     * @throws IOException
     */
    public function testCheckEndOfFile(): void
    {
        $path = $this->getTmpPath() . "/test";
        file_put_contents($path, "test");
        $element = $this->createElement($path);
        $this->assertFalse($element->isEndOfFile());
        $element->read(5);
        $this->assertTrue($element->isEndOfFile());
    }
}