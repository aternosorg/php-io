<?php

namespace Aternos\IO\Test\Unit\Filesystem;

use Aternos\IO\Exception\MoveException;
use Aternos\IO\Exception\PathOutsideElementException;
use Aternos\IO\Filesystem\Directory;
use Aternos\IO\Filesystem\FilesystemElement;
use Aternos\IO\Filesystem\FilesystemInterface;
use Aternos\IO\Interfaces\Features\CreateInterface;
use Aternos\IO\Interfaces\IOElementInterface;

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

    public function testGetPath(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $this->assertEquals($path, $element->getPath());
    }

    public function testGetName(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $this->assertEquals("test", $element->getName());
    }

    /**
     * @throws PathOutsideElementException
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
     */
    public function testGetRelativePathInOtherSubDirectory(): void
    {
        $path = $this->getTmpPath() . "/sub-dir/test";
        $element = $this->createElement($path);
        $directory = new Directory($this->getTmpPath() . "/other-dir");
        $this->assertEquals("../sub-dir/test", $element->getRelativePathTo($directory, true));
    }

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

    public function testCheckIfElementExists(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $this->assertFalse($element->exists());
        $this->create($element);
        $this->assertTrue($element->exists());
    }

    public function testDelete(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $this->create($element);
        $this->assertExists($path);
        $this->assertSame($element, $element->delete());
        $this->assertFalse(file_exists($path));
    }

    public function testDeleteNonExisting(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $this->assertFalse(file_exists($path));
        $element->delete();
        $this->assertFalse(file_exists($path));
    }

    public function testSerialize(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $serialized = serialize($element);
        $this->assertIsString($serialized);
    }

    public function testSerializeContainsPath(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $serialized = $element->__serialize();
        $this->assertArrayHasKey("path", $serialized);
        $this->assertEquals($path, $serialized["path"]);
    }
}