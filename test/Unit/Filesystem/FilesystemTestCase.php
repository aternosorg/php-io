<?php

namespace Aternos\IO\Test\Unit\Filesystem;

use Aternos\IO\Exception\PathOutsideElementException;
use Aternos\IO\Exception\MoveException;
use Aternos\IO\Filesystem\Directory;
use Aternos\IO\Filesystem\FilesystemElement;

abstract class FilesystemTestCase extends TmpDirTestCase
{
    /**
     * @param string $path
     * @return FilesystemElement
     */
    abstract protected function createElement(string $path): FilesystemElement;

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
        $element->create();
        $element->move($newPath);
        $this->assertEquals($newPath, $element->getPath());
        $this->assertFileExists($newPath);
        $this->assertFileDoesNotExist($path);
    }

    public function testThrowsExceptionOnImpossibleMove(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $newPath = $this->getTmpPath() . "/sub-dir/new-test";
        $element->create();
        $this->expectException(MoveException::class);
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
        $element->create();
        $element->changeName($newName);
        $this->assertEquals($newName, $element->getName());
        $this->assertFileExists($this->getTmpPath() . "/" . $newName);
        $this->assertFileDoesNotExist($path);
    }
}