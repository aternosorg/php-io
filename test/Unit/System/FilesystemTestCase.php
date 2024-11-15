<?php

namespace Aternos\IO\Test\Unit\System;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Exception\MoveException;
use Aternos\IO\Exception\PathOutsideElementException;
use Aternos\IO\Exception\StatException;
use Aternos\IO\Exception\TouchException;
use Aternos\IO\Interfaces\Features\CreateInterface;
use Aternos\IO\Interfaces\IOElementInterface;
use Aternos\IO\System\Directory\Directory;
use Aternos\IO\System\FilesystemElement;
use Aternos\IO\System\FilesystemInterface;

abstract class FilesystemTestCase extends TmpDirTestCase
{
    /**
     * @param string $path
     * @return FilesystemElement|IOElementInterface
     */
    abstract protected function createElement(string $path): FilesystemElement|IOElementInterface;

    /**
     * @param FilesystemInterface $element
     * @return void
     * @throws IOException
     */
    protected function create(FilesystemInterface $element): void
    {
        if ($element instanceof CreateInterface) {
            $element->create();
        }
    }

    /**
     * @param string $path
     * @return void
     */
    protected function assertExists(string $path): void
    {
        $this->assertFileExists($path);
    }

    /**
     * @param string $path
     * @param FilesystemInterface[] $elements
     * @return FilesystemInterface
     * @throws IOException
     */
    protected function getByPathFromArray(string $path, array $elements): FilesystemInterface
    {
        foreach ($elements as $element) {
            if ($element->getPath() === $path) {
                return $element;
            }
        }
        $this->fail("Element with path '" . $path . "' not found in array.");
    }

    /**
     * @param string $path
     * @param class-string<FilesystemInterface> $type
     * @param FilesystemInterface[] $elements
     * @throws IOException
     */
    protected function assertPathHasTypeInArray(string $path, string $type, array $elements): void
    {
        $element = $this->getByPathFromArray($path, $elements);
        $this->assertInstanceOf($type, $element);
    }

    /**
     * @return void
     * @throws IOException
     */
    public function testGetPath(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $this->assertEquals($path, $element->getPath());
    }

    /**
     * @return void
     * @throws IOException
     */
    public function testGetName(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $this->assertEquals("test", $element->getName());
    }

    /**
     * @throws PathOutsideElementException
     * @throws IOException
     */
    public function testGetRelativePath(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $directory = new Directory($this->getTmpPath());
        $this->assertEquals("test", $element->getRelativePathTo($directory));
    }

    /**
     * @throws PathOutsideElementException
     * @throws IOException
     */
    public function testGetRelativePathOutsideElement(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $directory = new Directory($this->getTmpPath() . "/sub-dir");
        $this->assertEquals("../test", $element->getRelativePathTo($directory, true));
    }

    /**
     * @throws PathOutsideElementException
     * @throws IOException
     */
    public function testGetRelativePathMultipleLayersOutsideElement(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $directory = new Directory($this->getTmpPath() . "/sub-dir/sub-sub-dir/sub-sub-sub-dir");
        $this->assertEquals("../../../test", $element->getRelativePathTo($directory, true));
    }

    /**
     * @return void
     * @throws PathOutsideElementException
     * @throws IOException
     */
    public function testGetRelativePathInOtherSubDirectory(): void
    {
        $path = $this->getTmpPath() . "/sub-dir/test";
        $element = $this->createElement($path);
        $directory = new Directory($this->getTmpPath() . "/other-dir");
        $this->assertEquals("../sub-dir/test", $element->getRelativePathTo($directory, true));
    }

    /**
     * @return void
     * @throws IOException
     * @throws PathOutsideElementException
     */
    public function testThrowsExceptionOnUnallowedRelativePathOutsideElement(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $directory = new Directory($this->getTmpPath() . "/sub-dir");
        $this->expectException(PathOutsideElementException::class);
        $this->expectExceptionMessage("Path is outside of element (" . $this->getTmpPath() . "/sub-dir -> " . $path . ")");
        $element->getRelativePathTo($directory);
    }

    /**
     * @return void
     * @throws MoveException
     * @throws IOException
     */
    public function testMovePath(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $newPath = $this->getTmpPath() . "/new-test";
        $this->create($element);
        $element->move($newPath);
        $this->assertEquals($newPath, $element->getPath());
        $this->assertExists($newPath);
        $this->assertFileDoesNotExist($path);
    }

    /**
     * @return void
     * @throws IOException
     * @throws MoveException
     */
    public function testThrowsExceptionOnImpossibleMove(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $newPath = $this->getTmpPath() . "/sub-dir/new-test";
        $this->create($element);
        $this->expectException(MoveException::class);
        $this->expectExceptionMessage("Could not move element (" . $path . " -> " . $newPath . ")");
        $element->move($newPath);
    }

    /**
     * @throws MoveException
     * @throws IOException
     */
    public function testChangeName(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $newName = "new-test";
        $this->create($element);
        $element->changeName($newName);
        $this->assertEquals($newName, $element->getName());
        $this->assertExists($this->getTmpPath() . "/" . $newName);
        $this->assertFileDoesNotExist($path);
    }

    /**
     * @return void
     * @throws IOException
     */
    public function testCheckIfElementExists(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $this->assertFalse($element->exists());
        $this->create($element);
        $this->assertTrue($element->exists());
    }

    /**
     * @return void
     * @throws IOException
     */
    public function testDelete(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $this->create($element);
        $this->assertExists($path);
        $this->assertSame($element, $element->delete());
        $this->assertFalse(file_exists($path));
    }

    /**
     * @return void
     * @throws IOException
     */
    public function testDeleteNonExisting(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $this->assertFalse(file_exists($path));
        $element->delete();
        $this->assertFalse(file_exists($path));
    }

    /**
     * @return void
     * @throws IOException
     * @throws StatException
     */
    public function testGetModificationTimestamp(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $this->create($element);
        $this->assertIsInt($element->getModificationTimestamp());
        $this->assertEquals(filemtime($path), $element->getModificationTimestamp());
    }

    /**
     * @return void
     * @throws IOException
     * @throws StatException
     */
    public function testGetModificationTimestampThrowsException(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $this->expectException(StatException::class);
        /** @noinspection SpellCheckingInspection */
        $this->expectExceptionMessage("Could not get modification timestamp (" . $path . "): filemtime(): stat failed for " . $path);
        $element->getModificationTimestamp();
    }

    /**
     * @return void
     * @throws IOException
     * @throws StatException
     */
    public function testGetAccessTimestamp(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $this->create($element);
        $this->assertIsInt($element->getAccessTimestamp());
        $this->assertEquals(fileatime($path), $element->getAccessTimestamp());
    }

    /**
     * @return void
     * @throws IOException
     * @throws StatException
     */
    public function testGetAccessTimestampThrowsException(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $this->expectException(StatException::class);
        /** @noinspection SpellCheckingInspection */
        $this->expectExceptionMessage("Could not get access timestamp (" . $path . "): fileatime(): stat failed for " . $path);
        $element->getAccessTimestamp();
    }

    /**
     * @return void
     * @throws IOException
     * @throws StatException
     */
    public function testGetStatusChangeTimestamp(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $this->create($element);
        $this->assertIsInt($element->getStatusChangeTimestamp());
        $this->assertEquals(filectime($path), $element->getStatusChangeTimestamp());
    }

    /**
     * @return void
     * @throws IOException
     * @throws StatException
     */
    public function testGetStatusChangeTimestampThrowsException(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $this->expectException(StatException::class);
        /** @noinspection SpellCheckingInspection */
        $this->expectExceptionMessage("Could not get status change timestamp (" . $path . "): filectime(): stat failed for " . $path);
        $element->getStatusChangeTimestamp();
    }

    /**
     * @return void
     * @throws IOException
     * @throws StatException
     * @throws TouchException
     */
    public function testSetAccessTimestamp(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $this->create($element);
        $timestamp = 1234567890;
        $element->setAccessTimestamp($timestamp);
        $this->assertEquals($timestamp, fileatime($path));
        $this->assertEquals($timestamp, $element->getAccessTimestamp());
    }

    /**
     * @return void
     * @throws IOException
     * @throws StatException
     * @throws TouchException
     */
    public function testSetAccessTimestampThrowsExceptionOnMissingElement(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $this->expectException(StatException::class);
        /** @noinspection SpellCheckingInspection */
        $this->expectExceptionMessage("Could not set access timestamp because element does not exist (" . $path . ")");
        $element->setAccessTimestamp(1234567890);
    }

    /**
     * @return void
     * @throws IOException
     * @throws StatException
     * @throws TouchException
     */
    public function testSetAccessTimestampThrowsExceptionOnImpossibleSet(): void
    {
        $element = $this->createElement("/");
        $this->expectException(TouchException::class);
        $this->expectExceptionMessage("Could not set access timestamp (" . $element->getPath() . ")");
        $element->setAccessTimestamp(1234567890);
    }

    /**
     * @return void
     * @throws IOException
     * @throws StatException
     * @throws TouchException
     */
    public function testSetModificationTimestamp(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $this->create($element);
        $timestamp = 1234567890;
        $element->setModificationTimestamp($timestamp);
        $this->assertEquals($timestamp, filemtime($path));
        $this->assertEquals($timestamp, $element->getModificationTimestamp());
    }

    /**
     * @return void
     * @throws IOException
     * @throws StatException
     * @throws TouchException
     */
    public function testSetModificationTimestampThrowsExceptionOnMissingElement(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $this->expectException(StatException::class);
        /** @noinspection SpellCheckingInspection */
        $this->expectExceptionMessage("Could not set modification timestamp because element does not exist (" . $path . ")");
        $element->setModificationTimestamp(1234567890);
    }

    /**
     * @return void
     * @throws IOException
     * @throws StatException
     * @throws TouchException
     */
    public function testSetModificationTimestampThrowsExceptionOnImpossibleSet(): void
    {
        $element = $this->createElement("/");
        $this->expectException(TouchException::class);
        $this->expectExceptionMessage("Could not set modification timestamp (" . $element->getPath() . ")");
        $element->setModificationTimestamp(1234567890);
    }

    /**
     * @return void
     */
    public function testSerialize(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $serialized = serialize($element);
        $this->assertIsString($serialized);
    }

    /**
     * @return void
     */
    public function testSerializeContainsPath(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $serialized = $element->__serialize();
        $this->assertArrayHasKey("path", $serialized);
        $this->assertEquals($path, $serialized["path"]);
    }
}