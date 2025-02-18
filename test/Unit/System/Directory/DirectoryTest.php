<?php

namespace Aternos\IO\Test\Unit\System\Directory;

use Aternos\IO\Exception\CreateDirectoryException;
use Aternos\IO\Exception\DeleteException;
use Aternos\IO\Exception\GetTargetException;
use Aternos\IO\Exception\IOException;
use Aternos\IO\Exception\MissingPermissionsException;
use Aternos\IO\Interfaces\Features\GetChildrenInterface;
use Aternos\IO\Interfaces\Features\ReadInterface;
use Aternos\IO\Interfaces\Features\WriteInterface;
use Aternos\IO\Interfaces\IOElementInterface;
use Aternos\IO\Interfaces\Types\DirectoryInterface;
use Aternos\IO\Interfaces\Types\FileInterface;
use Aternos\IO\Interfaces\Types\Link\LinkInterface;
use Aternos\IO\System\Directory\Directory;
use Aternos\IO\System\File\File;
use Aternos\IO\System\FilesystemElement;
use Aternos\IO\System\Link\DirectoryLink;
use Aternos\IO\System\Link\FileLink;
use Aternos\IO\System\Link\Link;
use Aternos\IO\Test\Unit\System\FilesystemTestCase;
use Generator;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\TestWith;

class DirectoryTest extends FilesystemTestCase
{
    protected function createElement(string $path): Directory
    {
        return new Directory($path);
    }

    /**
     * @throws MissingPermissionsException
     * @throws GetTargetException
     * @throws IOException
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

        $this->assertPathHasTypeInArray($path . "/dir2", DirectoryInterface::class, $children);
        $this->assertPathHasTypeInArray($path . "/dir1", DirectoryInterface::class, $children);
        $this->assertPathHasTypeInArray($path . "/file2", FileInterface::class, $children);
        $this->assertPathHasTypeInArray($path . "/file1", FileInterface::class, $children);
    }

    /**
     * @throws GetTargetException
     * @throws IOException
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
     * @throws IOException
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

        $this->assertPathHasTypeInArray($path . "/dir2", DirectoryInterface::class, $children);
        $this->assertPathHasTypeInArray($path . "/dir2/file3", FileInterface::class, $children);
        $this->assertPathHasTypeInArray($path . "/dir1", DirectoryInterface::class, $children);
        $this->assertPathHasTypeInArray($path . "/file2", FileInterface::class, $children);
        $this->assertPathHasTypeInArray($path . "/file1", FileInterface::class, $children);
    }

    /**
     * @return void
     * @throws MissingPermissionsException
     * @throws GetTargetException
     * @throws IOException
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
     * @param class-string[] $features
     * @param class-string $expected
     * @return void
     * @throws IOException
     */
    #[TestWith([FileInterface::class])]
    #[TestWith([File::class])]
    #[TestWith([DirectoryInterface::class])]
    #[TestWith([Directory::class])]
    #[TestWith([LinkInterface::class])]
    #[TestWith([FileLink::class])]
    #[TestWith([DirectoryLink::class])]
    #[TestWith([Link::class])]
    #[TestWith([[WriteInterface::class, ReadInterface::class], File::class])]
    #[TestWith([[GetChildrenInterface::class], Directory::class])]
    public function testGetChild(array|string $features, ?string $expected = null): void
    {
        if (is_string($features)) {
            $features = [$features];
        }

        if ($expected === null) {
            $expected = $features[0];
        }

        $path = $this->getTmpPath();
        $element = $this->createElement($path);

        $child = $element->getChild("test", ...$features);
        $this->assertInstanceOf($expected, $child);
        $this->assertEquals($path . "/test", $child->getPath());
    }

    /**
     * @return void
     * @throws IOException
     */
    public function testGetChildThrowsExceptionOnInvalidFeatureCombination(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("No supported child class found for features: Aternos\IO\Interfaces\Features\WriteInterface, Aternos\IO\Interfaces\Features\GetChildrenInterface");
        $element = $this->createElement($this->getTmpPath());
        $element->getChild("test", WriteInterface::class, GetChildrenInterface::class);
    }

    /**
     * @throws GetTargetException
     * @throws MissingPermissionsException
     * @throws IOException
     */
    public function testThrowsExceptionOnImpossibleDelete(): void
    {
        $element = $this->createElement("/dev/null");
        $this->expectException(DeleteException::class);
        $this->expectExceptionMessage("Could not delete directory (/dev/null)");
        $element->delete();
    }

    /**
     * @return void
     * @throws CreateDirectoryException
     * @throws IOException
     */
    public function testThrowsExceptionOnFailedCreation(): void
    {
        $this->expectException(CreateDirectoryException::class);
        $this->expectExceptionMessage("Could not create directory (/dev/null/test)");
        $element = $this->createElement("/dev/null/test");
        $element->create();
    }

    /**
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
     * @throws DeleteException
     * @throws GetTargetException
     * @throws MissingPermissionsException
     * @throws IOException
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
     * @throws IOException
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

        $this->assertPathHasTypeInArray($path . "/link1", FileLink::class, $children);
        $this->assertPathHasTypeInArray($path . "/link2", DirectoryLink::class, $children);
        $this->assertPathHasTypeInArray($path . "/link3", Link::class, $children);
    }

    /**
     * @throws GetTargetException
     * @throws MissingPermissionsException
     * @throws IOException
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

        $this->assertPathHasTypeInArray($path . "/link", LinkInterface::class, $children);
        $this->assertPathHasTypeInArray($path . "/dir", DirectoryInterface::class, $children);
        $this->assertPathHasTypeInArray($path . "/dir/file", FileInterface::class, $children);
    }

    /**
     * @throws GetTargetException
     * @throws MissingPermissionsException
     * @throws IOException
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
     * @throws IOException
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
     * @throws IOException
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

        $element = $this->getByPathFromArray($path . "/dir/link", $children);
        $this->assertInstanceOf(FileLink::class, $element);
        $target = $element->getTarget();
        $this->assertInstanceOf(FileInterface::class, $target);
        /** @var FileInterface $target */
        $this->assertEquals($path . "/file", $target->getPath());
    }

    /**
     * @throws GetTargetException
     * @throws MissingPermissionsException
     * @throws IOException
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

        $this->assertPathHasTypeInArray($path . "/dir/sub-dir", DirectoryInterface::class, $children);
        $element = $this->getByPathFromArray($path . "/dir/sub-dir/link", $children);
        $this->assertInstanceOf(FileLink::class, $element);
        $target = $element->getTarget();
        $this->assertInstanceOf(FileInterface::class, $target);
        /** @var FileInterface $target */
        $this->assertEquals($path . "/file", $target->getPath());
    }

    /**
     * @throws GetTargetException
     * @throws MissingPermissionsException
     * @throws IOException
     */
    public function testIgnoreOutsideLinkChains(): void
    {
        $path = $this->getTmpPath();
        mkdir($path . "/dir");
        touch($path . "/dir/file");
        symlink($path . "/dir/file", $path . "/outside-link");
        symlink($path . "/outside-link", $path . "/dir/inside-link");

        $directory = $this->createElement($path . "/dir");
        $children = $directory->getChildren();
        $this->assertInstanceOf(Generator::class, $children);
        $children = iterator_to_array($children);
        $this->assertCount(1, $children);
        $this->assertPathHasTypeInArray($path . "/dir/file", FileInterface::class, $children);
    }

    /**
     * @throws GetTargetException
     * @throws MissingPermissionsException
     * @throws IOException
     */
    public function testAllowOutsideLinkChains(): void
    {
        $path = $this->getTmpPath();
        mkdir($path . "/dir");
        touch($path . "/dir/file");
        symlink($path . "/dir/file", $path . "/outside-link");
        symlink($path . "/outside-link", $path . "/dir/inside-link");

        $directory = $this->createElement($path . "/dir");
        $children = $directory->getChildren(allowOutsideLinks: true);
        $this->assertInstanceOf(Generator::class, $children);
        $children = iterator_to_array($children);
        $this->assertCount(2, $children);

        $this->assertPathHasTypeInArray($path . "/dir/inside-link", FileLink::class, $children);
        $this->assertPathHasTypeInArray($path . "/dir/file", FileInterface::class, $children);
    }

    /**
     * @throws GetTargetException
     * @throws MissingPermissionsException
     * @throws IOException
     */
    public function testIgnoreInfiniteLinkLoops(): void
    {
        symlink($this->getTmpPath() . "/a", $this->getTmpPath() . "/b");
        symlink($this->getTmpPath() . "/b", $this->getTmpPath() . "/c");
        symlink($this->getTmpPath() . "/c", $this->getTmpPath() . "/a");

        $directory = $this->createElement($this->getTmpPath());
        $children = $directory->getChildren();
        $this->assertInstanceOf(Generator::class, $children);
        $children = iterator_to_array($children);
        $this->assertCount(0, $children);
    }

    /**
     * @throws GetTargetException
     * @throws MissingPermissionsException
     * @throws IOException
     */
    public function testIgnoreTooManyLinks(): void
    {
        $path = $this->getTmpPath();
        for ($i = 0; $i < 41; $i++) {
            symlink($path . "/" . $i, $path . "/" . ($i + 1));
        }

        $directory = $this->createElement($this->getTmpPath());
        $children = $directory->getChildren();
        $this->assertInstanceOf(Generator::class, $children);
        $children = iterator_to_array($children);
        $this->assertCount(40, $children);
        foreach ($children as $child) {
            $this->assertNotEquals($path . "/41", $child->getPath());
        }
    }

    /**
     * @return void
     * @throws GetTargetException
     * @throws MissingPermissionsException
     * @throws IOException
     */
    public function testLimitRecursiveDepth(): void
    {
        $path = $this->getTmpPath();
        mkdir($path . "/dir");
        symlink($path . "/dir", $path . "/dir/link");

        $directory = $this->createElement($path);
        $children = $directory->getChildrenRecursive();
        $this->assertInstanceOf(Generator::class, $children);
        $children = iterator_to_array($children);
        $this->assertCount(100, $children);
    }
}