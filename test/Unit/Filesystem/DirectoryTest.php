<?php

namespace Aternos\IO\Test\Unit\Filesystem;

use Aternos\IO\Exception\MissingPermissionsException;
use Aternos\IO\Filesystem\Directory;
use Aternos\IO\Interfaces\Types\DirectoryInterface;
use Aternos\IO\Interfaces\Types\File\FileInterface;
use Generator;

class DirectoryTest extends FilesystemTestCase
{
    protected string $directoryClass = DirectoryInterface::class;
    protected string $fileClass = FileInterface::class;

    protected function createElement(string $path): Directory
    {
        return new Directory($path);
    }

    /**
     * @throws MissingPermissionsException
     */
    public function testGetChildren(): void
    {
        $path = $this->getTmpPath();
        touch($path . "/file1");
        touch($path . "/file2");
        mkdir($path . "/dir1");
        mkdir($path . "/dir2");
        touch($path . "/dir2/file3");

        $directory = $this->createElement($path);
        $children = $directory->getChildren();
        $this->assertInstanceOf(Generator::class, $children);
        $children = iterator_to_array($children);
        $this->assertCount(4, $children);
        $this->assertContainsOnlyInstancesOf(\Aternos\IO\Interfaces\IOElementInterface::class, $children);

        $this->assertInstanceOf($this->directoryClass, $children[0]);
        $this->assertEquals($path . "/dir2", $children[0]->getPath());
        $this->assertInstanceOf($this->directoryClass, $children[1]);
        $this->assertEquals($path . "/dir1", $children[1]->getPath());
        $this->assertInstanceOf($this->fileClass, $children[2]);
        $this->assertEquals($path . "/file2", $children[2]->getPath());
        $this->assertInstanceOf($this->fileClass, $children[3]);
        $this->assertEquals($path . "/file1", $children[3]->getPath());
    }

    public function testThrowsExceptionOnGetChildrenWithMissingPermissions(): void
    {
        $path = $this->getTmpPath() . "/test";
        mkdir($path);
        chmod($path, 0333);

        $this->expectException(MissingPermissionsException::class);

        $directory = $this->createElement($path);
        iterator_to_array($directory->getChildren());

        chmod($path, 0777);
    }

    /**
     * @throws MissingPermissionsException
     */
    public function testGetChildrenRecursive(): void
    {
        $path = $this->getTmpPath();
        touch($path . "/file1");
        touch($path . "/file2");
        mkdir($path . "/dir1");
        mkdir($path . "/dir2");
        touch($path . "/dir2/file3");

        $directory = $this->createElement($path);
        $children = $directory->getChildrenRecursive();
        $this->assertInstanceOf(Generator::class, $children);
        $children = iterator_to_array($children);
        $this->assertCount(5, $children);
        $this->assertContainsOnlyInstancesOf(\Aternos\IO\Interfaces\IOElementInterface::class, $children);

        $this->assertInstanceOf($this->directoryClass, $children[0]);
        $this->assertEquals($path . "/dir2", $children[0]->getPath());
        $this->assertInstanceOf($this->fileClass, $children[1]);
        $this->assertEquals($path . "/dir2/file3", $children[1]->getPath());
        $this->assertInstanceOf($this->directoryClass, $children[2]);
        $this->assertEquals($path . "/dir1", $children[2]->getPath());
        $this->assertInstanceOf($this->fileClass, $children[3]);
        $this->assertEquals($path . "/file2", $children[3]->getPath());
        $this->assertInstanceOf($this->fileClass, $children[4]);
        $this->assertEquals($path . "/file1", $children[4]->getPath());
    }

    /**
     * @return void
     * @throws MissingPermissionsException
     */
    public function testThrowsExceptionOnGetChildrenRecursiveWithMissingPermissions(): void
    {
        $path = $this->getTmpPath() . "/test";
        mkdir($path);
        chmod($path, 0333);

        $this->expectException(MissingPermissionsException::class);

        $directory = $this->createElement($path);
        iterator_to_array($directory->getChildrenRecursive());

        chmod($path, 0777);
    }
}