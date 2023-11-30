<?php

namespace Aternos\IO\Test\Unit\System\Link;

use Aternos\IO\Exception\DeleteException;
use Aternos\IO\Exception\GetTargetException;
use Aternos\IO\Exception\MissingPermissionsException;
use Aternos\IO\Exception\SetTargetException;
use Aternos\IO\System\Directory\Directory;
use Aternos\IO\System\File\File;
use Aternos\IO\System\Link\DirectoryLink;
use Aternos\IO\Interfaces\IOElementInterface;
use Aternos\IO\Interfaces\Types\DirectoryInterface;
use Aternos\IO\Interfaces\Types\FileInterface;
use Generator;

class DirectoryLinkTest extends LinkTest
{
    protected function createElement(string $path): DirectoryLink
    {
        return new DirectoryLink($path);
    }

    /**
     * @throws GetTargetException
     */
    public function testGetTarget(): void
    {
        mkdir($this->getTmpPath() . "/test-target");
        symlink($this->getTmpPath() . "/test-target", $this->getTmpPath() . "/test");
        $element = $this->createElement($this->getTmpPath() . "/test");
        $target = $element->getTarget();
        $this->assertInstanceOf(Directory::class, $target);
        $this->assertEquals($this->getTmpPath() . "/test-target", $target->getPath());
    }

    /**
     * @return void
     * @throws GetTargetException
     */
    public function testGetTargetTwiceReturnsSameObject(): void
    {
        mkdir($this->getTmpPath() . "/test-target");
        symlink($this->getTmpPath() . "/test-target", $this->getTmpPath() . "/test");
        $element = $this->createElement($this->getTmpPath() . "/test");
        $target = $element->getTarget();
        $this->assertSame($target, $element->getTarget());
    }

    public function testThrowsExceptionOnGetTargetWithInvalidTarget(): void
    {
        touch($this->getTmpPath() . "/test-target");
        symlink($this->getTmpPath() . "/test-target", $this->getTmpPath() . "/test");
        $element = $this->createElement($this->getTmpPath() . "/test");
        $this->expectException(GetTargetException::class);
        $this->expectExceptionMessage("Could not get directory link target because link target is not a directory (" . $this->getTmpPath() . "/test" . ")");
        $element->getTarget();
    }

    /**
     * @throws GetTargetException
     */
    public function testGetTargetPath(): void
    {
        mkdir($this->getTmpPath() . "/test-target");
        symlink($this->getTmpPath() . "/test-target", $this->getTmpPath() . "/test");
        $element = $this->createElement($this->getTmpPath() . "/test");
        $this->assertEquals($this->getTmpPath() . "/test-target", $element->getTargetPath());
    }

    /**
     * @throws GetTargetException
     */
    public function testGetFinalTargetPath(): void
    {
        mkdir($this->getTmpPath() . "/test-target");
        symlink($this->getTmpPath() . "/test-target", $this->getTmpPath() . "/test1");
        symlink($this->getTmpPath() . "/test1", $this->getTmpPath() . "/test2");
        symlink($this->getTmpPath() . "/test2", $this->getTmpPath() . "/test3");
        $element = $this->createElement($this->getTmpPath() . "/test3");
        $this->assertEquals($this->getTmpPath() . "/test-target", $element->getFinalTargetPath());
    }

    /**
     * @throws GetTargetException
     */
    public function testGetTargetOnLinkChainGetsLink(): void
    {
        mkdir($this->getTmpPath() . "/test-target");
        symlink($this->getTmpPath() . "/test-target", $this->getTmpPath() . "/test1");
        symlink($this->getTmpPath() . "/test1", $this->getTmpPath() . "/test2");
        symlink($this->getTmpPath() . "/test2", $this->getTmpPath() . "/test3");
        $element = $this->createElement($this->getTmpPath() . "/test3");
        $target = $element->getTarget();
        $this->assertInstanceOf(DirectoryLink::class, $target);
        $this->assertEquals($this->getTmpPath() . "/test2", $target->getPath());
    }

    /**
     * @throws GetTargetException
     */
    public function testGetFinalTarget(): void
    {
        mkdir($this->getTmpPath() . "/test-target");
        symlink($this->getTmpPath() . "/test-target", $this->getTmpPath() . "/test1");
        symlink($this->getTmpPath() . "/test1", $this->getTmpPath() . "/test2");
        symlink($this->getTmpPath() . "/test2", $this->getTmpPath() . "/test3");
        $element = $this->createElement($this->getTmpPath() . "/test3");
        $target = $element->getFinalTarget();
        $this->assertInstanceOf(Directory::class, $target);
        $this->assertEquals($this->getTmpPath() . "/test-target", $target->getPath());
    }

    /**
     * @throws SetTargetException
     * @throws DeleteException
     */
    public function testSetTarget(): void
    {
        mkdir($this->getTmpPath() . "/test-target");
        $element = $this->createElement($this->getTmpPath() . "/test");
        $element->setTarget(new Directory($this->getTmpPath() . "/test-target"));
        $this->assertTrue(is_link($this->getTmpPath() . "/test"));
        $this->assertEquals($this->getTmpPath() . "/test-target", readlink($this->getTmpPath() . "/test"));
    }

    /**
     * @throws SetTargetException
     * @throws DeleteException
     */
    public function testSetTargetReplacesTarget(): void
    {
        mkdir($this->getTmpPath() . "/test-target");
        mkdir($this->getTmpPath() . "/test-target-2");
        symlink($this->getTmpPath() . "/test-target", $this->getTmpPath() . "/test");
        $element = $this->createElement($this->getTmpPath() . "/test");
        $element->setTarget(new Directory($this->getTmpPath() . "/test-target-2"));
        $this->assertTrue(is_link($this->getTmpPath() . "/test"));
        $this->assertEquals($this->getTmpPath() . "/test-target-2", readlink($this->getTmpPath() . "/test"));
    }

    /**
     * @throws DeleteException
     */
    public function testThrowsExceptionOnImpossibleSetTarget(): void
    {
        $element = $this->createElement("/dev/null/test");
        $this->expectException(SetTargetException::class);
        $this->expectExceptionMessage("Could not set link target (/dev/null/test" . " -> " . $this->getTmpPath() . "/test-target" . ")");
        $element->setTarget(new Directory($this->getTmpPath() . "/test-target"));
    }

    /**
     * @throws DeleteException
     */
    public function testThrowsExceptionOnInvalidSetTarget(): void
    {
        $element = $this->createElement($this->getTmpPath() . "/test");
        $this->expectException(SetTargetException::class);
        $this->expectExceptionMessage("Could not set directory link target because target is not a directory (" . $this->getTmpPath() . "/test" . " -> " . $this->getTmpPath() . "/test-target" . ")");
        $element->setTarget(new File($this->getTmpPath() . "/test-target"));
    }

    /**
     * @throws GetTargetException
     * @throws SetTargetException
     * @throws DeleteException
     */
    public function testCreate(): void
    {
        $element = $this->createElement($this->getTmpPath() . "/test");
        $element->setTarget(new Directory($this->getTmpPath() . "/target"));
        $element->create();
        $this->assertTrue(is_link($this->getTmpPath() . "/test"));
        $this->assertEquals($this->getTmpPath() . "/target", readlink($this->getTmpPath() . "/test"));
    }

    /**
     * @throws GetTargetException
     */
    public function testGetChildren(): void
    {
        $path = $this->getTmpPath() . "/target";
        mkdir($path);
        touch($path . "/file1");
        touch($path . "/file2");
        mkdir($path . "/dir1");
        mkdir($path . "/dir2");
        touch($path . "/dir2/file3");

        symlink($path, $this->getTmpPath() . "/test");
        $directory = $this->createElement($this->getTmpPath() . "/test");
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
     * @throws MissingPermissionsException
     * @throws GetTargetException
     */
    public function testGetChildrenRecursive(): void
    {
        $path = $this->getTmpPath() . "/target";
        mkdir($path);
        touch($path . "/file1");
        touch($path . "/file2");
        mkdir($path . "/dir1");
        mkdir($path . "/dir2");
        touch($path . "/dir2/file3");

        symlink($path, $this->getTmpPath() . "/test");
        $directory = $this->createElement($this->getTmpPath() . "/test");
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
}