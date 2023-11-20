<?php

namespace Aternos\IO\Test\Unit\Filesystem;

use Aternos\IO\Exception\CreateDirectoryException;
use Aternos\IO\Exception\DeleteException;
use Aternos\IO\Exception\GetTargetException;
use Aternos\IO\Exception\MissingPermissionsException;
use Aternos\IO\Filesystem\Directory;
use Aternos\IO\Filesystem\Link\DirectoryLink;
use Aternos\IO\Filesystem\Link\FileLink;
use Aternos\IO\Filesystem\Link\Link;
use Aternos\IO\Interfaces\IOElementInterface;
use Aternos\IO\Interfaces\Types\DirectoryInterface;
use Aternos\IO\Interfaces\Types\FileInterface;
use Aternos\IO\Interfaces\Types\Link\LinkInterface;
use Generator;

class DirectoryTest extends FilesystemTestCase
{

    protected function createElement(string $path): Directory
    {
        return new Directory($path);
    }

    /**
     * @throws MissingPermissionsException
     * @throws GetTargetException
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
        $this->assertContainsOnlyInstancesOf(IOElementInterface::class, $children);

        $this->assertInstanceOf(DirectoryInterface::class, $children[0]);
        $this->assertEquals($path . "/dir2", $children[0]->getPath());
        $this->assertInstanceOf(DirectoryInterface::class, $children[1]);
        $this->assertEquals($path . "/dir1", $children[1]->getPath());
        $this->assertInstanceOf(FileInterface::class, $children[2]);
        $this->assertEquals($path . "/file2", $children[2]->getPath());
        $this->assertInstanceOf(FileInterface::class, $children[3]);
        $this->assertEquals($path . "/file1", $children[3]->getPath());
    }

    /**
     * @throws GetTargetException
     */
    public function testThrowsExceptionOnGetChildrenWithMissingPermissions(): void
    {
        $path = $this->getTmpPath() . "/test";
        mkdir($path);
        chmod($path, 0333);

        $this->expectException(MissingPermissionsException::class);
        $this->expectExceptionMessage("Could not read directory due to missing read permissions (" . $path . ")");

        $directory = $this->createElement($path);
        iterator_to_array($directory->getChildren());

        chmod($path, 0777);
    }

    /**
     * @throws MissingPermissionsException
     * @throws GetTargetException
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
        $this->assertContainsOnlyInstancesOf(IOElementInterface::class, $children);

        $this->assertInstanceOf(DirectoryInterface::class, $children[0]);
        $this->assertEquals($path . "/dir2", $children[0]->getPath());
        $this->assertInstanceOf(FileInterface::class, $children[1]);
        $this->assertEquals($path . "/dir2/file3", $children[1]->getPath());
        $this->assertInstanceOf(DirectoryInterface::class, $children[2]);
        $this->assertEquals($path . "/dir1", $children[2]->getPath());
        $this->assertInstanceOf(FileInterface::class, $children[3]);
        $this->assertEquals($path . "/file2", $children[3]->getPath());
        $this->assertInstanceOf(FileInterface::class, $children[4]);
        $this->assertEquals($path . "/file1", $children[4]->getPath());
    }

    /**
     * @return void
     * @throws MissingPermissionsException
     * @throws GetTargetException
     */
    public function testThrowsExceptionOnGetChildrenRecursiveWithMissingPermissions(): void
    {
        $path = $this->getTmpPath() . "/test";
        mkdir($path);
        chmod($path, 0333);

        $this->expectException(MissingPermissionsException::class);
        $this->expectExceptionMessage("Could not read directory due to missing read permissions (" . $path . ")");

        $directory = $this->createElement($path);
        iterator_to_array($directory->getChildrenRecursive());

        chmod($path, 0777);
    }

    /**
     * @throws GetTargetException
     * @throws MissingPermissionsException
     */
    public function testThrowsExceptionOnImpossibleDelete(): void
    {
        $element = $this->createElement("/dev/null");
        $this->expectException(DeleteException::class);
        $this->expectExceptionMessage("Could not delete directory (/dev/null)");
        $element->delete();
    }

    public function testThrowsExceptionOnFailedCreation(): void
    {
        $this->expectException(CreateDirectoryException::class);
        $this->expectExceptionMessage("Could not create directory (/dev/null/test)");
        $element = $this->createElement("/dev/null/test");
        $element->create();
    }

    /**
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
     * @throws DeleteException|MissingPermissionsException|GetTargetException
     */
    public function testDeleteRecursively(): void
    {
        $path = $this->getTmpPath() . "/test";
        mkdir($path);
        mkdir($path . "/dir1");
        mkdir($path . "/dir2");
        touch($path . "/dir2/file1");
        touch($path . "/dir2/file2");
        touch($path . "/file1");
        touch($path . "/file2");
        $element = $this->createElement($path);
        $this->assertTrue(file_exists($path));
        $element->delete();
        $this->assertFalse(file_exists($path));
    }

    /**
     * @throws GetTargetException
     * @throws MissingPermissionsException
     */
    public function testGetChildrenLinks(): void
    {
        $path = $this->getTmpPath();
        touch($path . "/file1");
        mkdir($path . "/dir1");
        symlink($path . "/file1", $path . "/link1");
        symlink($path . "/dir1", $path . "/link2");
        symlink($path . "/nonexistent", $path . "/link3");

        $directory = $this->createElement($path);
        $children = $directory->getChildren();
        $this->assertInstanceOf(Generator::class, $children);
        $children = iterator_to_array($children);
        $this->assertCount(5, $children);
        $this->assertInstanceOf(Link::class, $children[0]);
        $this->assertEquals($path . "/link3", $children[0]->getPath());
        $this->assertInstanceOf(DirectoryLink::class, $children[1]);
        $this->assertEquals($path . "/link2", $children[1]->getPath());
        $this->assertInstanceOf(FileLink::class, $children[2]);
        $this->assertEquals($path . "/link1", $children[2]->getPath());
    }

    /**
     * @throws GetTargetException
     * @throws MissingPermissionsException
     */
    public function testDontFollowLinks(): void
    {
        $path = $this->getTmpPath();
        mkdir($path . "/dir");
        touch($path . "/dir/file");
        symlink($path . "/dir", $path . "/link");

        $directory = $this->createElement($path);
        $children = $directory->getChildrenRecursive(followLinks: false);

        $this->assertInstanceOf(Generator::class, $children);
        $children = iterator_to_array($children);
        $this->assertCount(3, $children);
        $this->assertInstanceOf(LinkInterface::class, $children[0]);
        $this->assertEquals($path . "/link", $children[0]->getPath());
        $this->assertInstanceOf(DirectoryInterface::class, $children[1]);
        $this->assertEquals($path . "/dir", $children[1]->getPath());
        $this->assertInstanceOf(FileInterface::class, $children[2]);
        $this->assertEquals($path . "/dir/file", $children[2]->getPath());
    }

    /**
     * @throws GetTargetException
     * @throws MissingPermissionsException
     */
    public function testIgnoreOutsideLinks(): void
    {
        $path = $this->getTmpPath();
        touch($path . "/file");
        mkdir($path . "/dir");
        symlink($path . "/file", $path . "/dir/link");

        $directory = $this->createElement($path . "/dir");
        $children = $directory->getChildren();
        $this->assertInstanceOf(Generator::class, $children);
        $children = iterator_to_array($children);
        $this->assertCount(0, $children);
    }

    /**
     * @throws GetTargetException
     * @throws MissingPermissionsException
     */
    public function testIgnoreOutsideLinksRecursive(): void
    {
        $path = $this->getTmpPath();
        touch($path . "/file");
        mkdir($path . "/dir");
        mkdir($path . "/dir/sub-dir");
        symlink($path . "/file", $path . "/dir/sub-dir/link");

        $directory = $this->createElement($path . "/dir");
        $children = $directory->getChildrenRecursive();
        $this->assertInstanceOf(Generator::class, $children);
        $children = iterator_to_array($children);
        $this->assertCount(1, $children);
        $this->assertInstanceOf(DirectoryInterface::class, $children[0]);
        $this->assertEquals($path . "/dir/sub-dir", $children[0]->getPath());
    }

    /**
     * @throws GetTargetException
     * @throws MissingPermissionsException
     */
    public function testAllowOutsideLinks(): void
    {
        $path = $this->getTmpPath();
        touch($path . "/file");
        mkdir($path . "/dir");
        symlink($path . "/file", $path . "/dir/link");

        $directory = $this->createElement($path . "/dir");
        $children = $directory->getChildren(allowOutsideLinks: true);
        $this->assertInstanceOf(Generator::class, $children);
        $children = iterator_to_array($children);
        $this->assertCount(1, $children);
        $this->assertInstanceOf(FileLink::class, $children[0]);
        $this->assertEquals($path . "/dir/link", $children[0]->getPath());
        $this->assertInstanceOf(FileInterface::class, $children[0]->getTarget());
        $this->assertEquals($path . "/file", $children[0]->getTarget()->getPath());
    }

    /**
     * @throws GetTargetException
     * @throws MissingPermissionsException
     */
    public function testAllowOutsideLinksRecursive(): void
    {
        $path = $this->getTmpPath();
        touch($path . "/file");
        mkdir($path . "/dir");
        mkdir($path . "/dir/sub-dir");
        symlink($path . "/file", $path . "/dir/sub-dir/link");

        $directory = $this->createElement($path . "/dir");
        $children = $directory->getChildrenRecursive(allowOutsideLinks: true);
        $this->assertInstanceOf(Generator::class, $children);
        $children = iterator_to_array($children);
        $this->assertCount(2, $children);
        $this->assertInstanceOf(DirectoryInterface::class, $children[0]);
        $this->assertEquals($path . "/dir/sub-dir", $children[0]->getPath());
        $this->assertInstanceOf(FileLink::class, $children[1]);
        $this->assertEquals($path . "/dir/sub-dir/link", $children[1]->getPath());
        $this->assertInstanceOf(FileInterface::class, $children[1]->getTarget());
        $this->assertEquals($path . "/file", $children[1]->getTarget()->getPath());
    }
}