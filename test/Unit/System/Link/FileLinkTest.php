<?php

namespace Aternos\IO\Test\Unit\System\Link;

use Aternos\IO\Exception\DeleteException;
use Aternos\IO\Exception\GetTargetException;
use Aternos\IO\Exception\IOException;
use Aternos\IO\Exception\SetTargetException;
use Aternos\IO\System\Directory\Directory;
use Aternos\IO\System\File\File;
use Aternos\IO\System\Link\FileLink;
use Aternos\IO\Interfaces\Types\FileInterface;
use ReflectionException;
use ReflectionObject;

class FileLinkTest extends LinkTest
{
    protected function createElement(string $path): FileLink
    {
        return new FileLink($path);
    }

    /**
     * @throws GetTargetException
     */
    public function testGetTarget(): void
    {
        touch($this->getTmpPath() . "/test-target");
        symlink($this->getTmpPath() . "/test-target", $this->getTmpPath() . "/test");
        $element = $this->createElement($this->getTmpPath() . "/test");
        $target = $element->getTarget();
        $this->assertInstanceOf(FileInterface::class, $target);
        $this->assertEquals($this->getTmpPath() . "/test-target", $target->getPath());
    }

    /**
     * @return void
     * @throws GetTargetException
     */
    public function testGetTargetTwiceReturnsSameObject(): void
    {
        touch($this->getTmpPath() . "/test-target");
        symlink($this->getTmpPath() . "/test-target", $this->getTmpPath() . "/test");
        $element = $this->createElement($this->getTmpPath() . "/test");
        $target = $element->getTarget();
        $this->assertSame($target, $element->getTarget());
    }

    public function testThrowsExceptionOnGetTargetWithInvalidTarget(): void
    {
        mkdir($this->getTmpPath() . "/test-target");
        symlink($this->getTmpPath() . "/test-target", $this->getTmpPath() . "/test");
        $element = $this->createElement($this->getTmpPath() . "/test");
        $this->expectException(GetTargetException::class);
        $this->expectExceptionMessage("Could not get file link target because link target is not a file (" . $this->getTmpPath() . "/test" . ")");
        $element->getTarget();
    }

    /**
     * @throws GetTargetException
     */
    public function testGetTargetPath(): void
    {
        touch($this->getTmpPath() . "/test-target");
        symlink($this->getTmpPath() . "/test-target", $this->getTmpPath() . "/test");
        $element = $this->createElement($this->getTmpPath() . "/test");
        $this->assertEquals($this->getTmpPath() . "/test-target", $element->getTargetPath());
    }

    /**
     * @throws GetTargetException
     */
    public function testGetFinalTargetPath(): void
    {
        touch($this->getTmpPath() . "/test-target");
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
        touch($this->getTmpPath() . "/test-target");
        symlink($this->getTmpPath() . "/test-target", $this->getTmpPath() . "/test1");
        symlink($this->getTmpPath() . "/test1", $this->getTmpPath() . "/test2");
        symlink($this->getTmpPath() . "/test2", $this->getTmpPath() . "/test3");
        $element = $this->createElement($this->getTmpPath() . "/test3");
        $target = $element->getTarget();
        $this->assertInstanceOf(FileLink::class, $target);
        $this->assertEquals($this->getTmpPath() . "/test2", $target->getPath());
    }

    /**
     * @throws GetTargetException
     */
    public function testGetFinalTarget(): void
    {
        touch($this->getTmpPath() . "/test-target");
        symlink($this->getTmpPath() . "/test-target", $this->getTmpPath() . "/test1");
        symlink($this->getTmpPath() . "/test1", $this->getTmpPath() . "/test2");
        symlink($this->getTmpPath() . "/test2", $this->getTmpPath() . "/test3");
        $element = $this->createElement($this->getTmpPath() . "/test3");
        $target = $element->getFinalTarget();
        $this->assertInstanceOf(File::class, $target);
        $this->assertEquals($this->getTmpPath() . "/test-target", $target->getPath());
    }

    /**
     * @throws SetTargetException
     * @throws DeleteException
     */
    public function testSetTarget(): void
    {
        touch($this->getTmpPath() . "/test-target");
        $element = $this->createElement($this->getTmpPath() . "/test");
        $element->setTarget(new File($this->getTmpPath() . "/test-target"));
        $this->assertTrue(is_link($this->getTmpPath() . "/test"));
        $this->assertEquals($this->getTmpPath() . "/test-target", readlink($this->getTmpPath() . "/test"));
    }

    /**
     * @throws SetTargetException
     * @throws DeleteException
     */
    public function testSetTargetReplacesTarget(): void
    {
        touch($this->getTmpPath() . "/test-target");
        touch($this->getTmpPath() . "/test-target-2");
        symlink($this->getTmpPath() . "/test-target", $this->getTmpPath() . "/test");
        $element = $this->createElement($this->getTmpPath() . "/test");
        $element->setTarget(new File($this->getTmpPath() . "/test-target-2"));
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
        $element->setTarget(new File($this->getTmpPath() . "/test-target"));
    }

    /**
     * @throws DeleteException
     */
    public function testThrowsExceptionOnInvalidSetTarget(): void
    {
        $element = $this->createElement($this->getTmpPath() . "/test");
        $this->expectException(SetTargetException::class);
        $this->expectExceptionMessage("Could not set file link target because target is not a file (" . $this->getTmpPath() . "/test" . " -> " . $this->getTmpPath() . "/test-target" . ")");
        $element->setTarget(new Directory($this->getTmpPath() . "/test-target"));
    }

    /**
     * @throws IOException
     * @throws ReflectionException
     */
    public function testClose(): void
    {
        $path = $this->getTmpPath() . "/test";
        file_put_contents($path, "test");
        symlink($path, $this->getTmpPath() . "/test-link");
        $element = $this->createElement($this->getTmpPath() . "/test-link");

        $target = $element->getTarget();
        $element->read(4);
        $reflectionObject = new ReflectionObject($target);
        $file = $reflectionObject->getProperty("socketResource")->getValue($target);
        $this->assertIsResource($file);

        $element->close();

        $this->assertIsClosedResource($file);
        $null = $reflectionObject->getProperty("socketResource")->getValue($target);
        $this->assertNull($null);
    }

    /**
     * @return void
     * @throws DeleteException
     * @throws GetTargetException
     * @throws SetTargetException
     */
    public function testCreate(): void
    {
        $targetPath = $this->getTmpPath() . "/test-target";
        $element = $this->createElement($this->getTmpPath() . "/test");
        $element->setTarget(new File($targetPath));
        $element->create();
        $this->assertFileExists($targetPath);
    }

    /**
     * @return void
     * @throws DeleteException
     * @throws GetTargetException
     * @throws SetTargetException
     */
    public function testGetPosition(): void
    {
        $targetPath = $this->getTmpPath() . "/test-target";
        file_put_contents($targetPath, "0123456789");
        $element = $this->createElement($this->getTmpPath() . "/test");
        $element->setTarget(new File($targetPath));
        $this->assertEquals(0, $element->getPosition());
        $element->read(5);
        $this->assertEquals(5, $element->getPosition());
        $element->setPosition(3);
        $this->assertEquals(3, $element->getPosition());
    }

    /**
     * @return void
     * @throws DeleteException
     * @throws GetTargetException
     * @throws SetTargetException
     */
    public function testSetPosition(): void
    {
        $targetPath = $this->getTmpPath() . "/test-target";
        file_put_contents($targetPath, "0123456789");
        $element = $this->createElement($this->getTmpPath() . "/test");
        $element->setTarget(new File($targetPath));
        $element->setPosition(3);
        $this->assertEquals(3, $element->getPosition());
        $this->assertEquals("3", $element->read(1));
    }

    public function testGetSize(): void
    {
        $targetPath = $this->getTmpPath() . "/test-target";
        file_put_contents($targetPath, "0123456789");
        $element = $this->createElement($this->getTmpPath() . "/test");
        $element->setTarget(new File($targetPath));
        $this->assertEquals(10, $element->getSize());
    }

    /**
     * @return void
     * @throws DeleteException
     * @throws GetTargetException
     * @throws SetTargetException
     */
    public function testRead(): void
    {
        $targetPath = $this->getTmpPath() . "/test-target";
        file_put_contents($targetPath, "test");
        $element = $this->createElement($this->getTmpPath() . "/test");
        $element->setTarget(new File($targetPath));
        $this->assertEquals("test", $element->read(4));
    }

    /**
     * @return void
     * @throws DeleteException
     * @throws GetTargetException
     * @throws SetTargetException
     */
    public function testWrite(): void
    {
        $targetPath = $this->getTmpPath() . "/test-target";
        $element = $this->createElement($this->getTmpPath() . "/test");
        $element->setTarget(new File($targetPath));
        $element->write("test");
        $this->assertEquals("test", file_get_contents($targetPath));
    }

    /**
     * @return void
     * @throws DeleteException
     * @throws GetTargetException
     * @throws SetTargetException
     */
    public function testTruncate(): void
    {
        $targetPath = $this->getTmpPath() . "/test-target";
        file_put_contents($targetPath, "0123456789");
        $element = $this->createElement($this->getTmpPath() . "/test");
        $element->setTarget(new File($targetPath));
        $element->truncate(5);
        $this->assertEquals("01234", file_get_contents($targetPath));
    }

    /**
     * @throws IOException
     */
    public function testCheckEndOfFile(): void
    {
        $targetPath = $this->getTmpPath() . "/test-target";
        file_put_contents($targetPath, "test");
        symlink($targetPath, $this->getTmpPath() . "/test");
        $element = $this->createElement($this->getTmpPath() . "/test");
        $this->assertFalse($element->isEndOfFile());
        $element->read(5);
        $this->assertTrue($element->isEndOfFile());
    }
}