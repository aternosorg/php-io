<?php

namespace Aternos\IO\Test\Unit\System\File;

use Aternos\IO\Exception\CreateDirectoryException;
use Aternos\IO\Exception\CreateFileException;
use Aternos\IO\Exception\DeleteException;
use Aternos\IO\Exception\IOException;
use Aternos\IO\Exception\MissingPermissionsException;
use Aternos\IO\Exception\ReadException;
use Aternos\IO\Exception\SeekException;
use Aternos\IO\Exception\StatException;
use Aternos\IO\Exception\TellException;
use Aternos\IO\Exception\TruncateException;
use Aternos\IO\Exception\WriteException;
use Aternos\IO\Interfaces\Types\VolatileFileInterface;
use Aternos\IO\System\File\File;
use Aternos\IO\Test\Unit\System\FilesystemTestCase;
use PHPUnit\Framework\Attributes\WithoutErrorHandler;
use ReflectionException;
use ReflectionObject;

class FileTest extends FilesystemTestCase
{
    use VolatileFileTestTrait;

    protected function createElement(string $path): File
    {
        return new File($path);
    }

    /**
     * @throws IOException
     * @throws CreateDirectoryException
     * @throws CreateFileException
     */
    protected function getVolatileFile(): VolatileFileInterface
    {
        return (new File($this->getTmpPath() . "/test"))->create();
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
     * @throws StatException
     * @throws IOException
     */
    public function testGetSizeOfEmptyFile(): void
    {
        $path = $this->getTmpPath() . "/test";
        file_put_contents($path, "");
        $element = $this->createElement($path);
        $this->assertEquals(0, $element->getSize());
    }

    /**
     * @throws IOException
     */
    public function testThrowsExceptionOnGetSize(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $this->expectException(StatException::class);
        $this->expectExceptionMessage("Could not get file size: filesize(): stat failed for " . $path . " (" . $path . ")");
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
        $reflectionObject->getProperty("socketResource")->setValue($element, fopen($path, "w"));
        $this->expectException(ReadException::class);
        /** @noinspection SpellCheckingInspection */
        $this->expectExceptionMessage("Could not read from file: fread(): Read of 8192 bytes failed with errno=9 Bad file descriptor (" . $path . ")");
        $element->read(4);
    }

    /**
     * @throws ReadException
     */
    public function testThrowsExceptionOnInvalidPath(): void
    {
        $element = $this->createElement("/dev/null/test");
        $this->expectException(IOException::class);
        /** @noinspection SpellCheckingInspection */
        $this->expectExceptionMessage("Could not open file: fopen(/dev/null/test): Failed to open stream: No such file or directory (/dev/null/test)");
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
        /** @noinspection SpellCheckingInspection */
        $this->expectExceptionMessage("Could not read from file due to missing read permissions: fread(): Read of 8192 bytes failed with errno=9 Bad file descriptor (" . $path . ")");
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
     * @return void
     * @throws IOException
     */
    public function testThrowsExceptionOnImpossibleGetPosition(): void
    {
        $element = $this->createElement("/dev/null");
        $this->expectException(TellException::class);
        $this->expectExceptionMessage("Could not get file position (/dev/null)");
        $element->getPosition();
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
        $file = $reflectionObject->getProperty("socketResource")->getValue($element);
        $this->assertIsResource($file);

        $element->close();

        $this->assertIsClosedResource($file);
        $null = $reflectionObject->getProperty("socketResource")->getValue($element);
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
        $file = $reflectionObject->getProperty("socketResource")->getValue($element);
        $this->assertIsResource($file);

        $element->__destruct();

        $file = $reflectionObject->getProperty("socketResource")->getValue($element);
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
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        file_put_contents($path, "test");
        $reflectionObject = new ReflectionObject($element);
        $reflectionObject->getProperty("socketResource")->setValue($element, fopen($path, "r"));
        $this->expectException(TruncateException::class);
        $this->expectExceptionMessage("Could not truncate file (" . $path . ")");
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
        $reflectionObject->getProperty("socketResource")->setValue($element, fopen($path, "r"));
        $this->expectException(WriteException::class);
        /** @noinspection SpellCheckingInspection */
        $this->expectExceptionMessage("Could not write to file: fwrite(): Write of 4 bytes failed with errno=9 Bad file descriptor (" . $path . ")");
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
        /** @noinspection SpellCheckingInspection */
        $this->expectExceptionMessage("Could not write to file due to missing write permissions: fwrite(): Write of 4 bytes failed with errno=9 Bad file descriptor (" . $path . ")");
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

    /**
     * @throws IOException
     */
    public function testThrowsExceptionOnImpossibleDelete(): void
    {
        $element = $this->createElement("/dev/null");
        $this->expectException(DeleteException::class);
        $this->expectExceptionMessageMatches("/^Could not delete file: unlink\(\/dev\/null\): /");
        $element->delete();
    }

    /**
     * @throws IOException
     * @throws CreateDirectoryException
     */
    public function testThrowsExceptionOnFailedCreation(): void
    {
        $this->expectException(CreateFileException::class);
        $this->expectExceptionMessage("Could not create file: touch(): Unable to create file /dev/null/test because Not a directory (/dev/null/test)");
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
        $this->expectExceptionMessage("Could not open file due to missing read and write permissions (" . $path . ")");
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
     * @throws IOException
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
     * @throws IOException
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
        $this->assertArrayNotHasKey("socketResource", $serialized);
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

    /**
     * @throws ReflectionException
     */
    #[WithoutErrorHandler]
    public function testSocketThrowExceptionIncludesPreviousPhpError(): void
    {
        $element = $this->createElement($this->getTmpPath() . "/test");
        $reflectionObject = new ReflectionObject($element);
        $throwExceptionMethod = $reflectionObject->getMethod("throwException");

        @trigger_error("warning", E_USER_WARNING);
        $this->expectException(IOException::class);
        $this->expectExceptionMessage("Test: warning (" . $this->getTmpPath() . "/test)");
        $throwExceptionMethod->invoke($element, "Test");
    }
}