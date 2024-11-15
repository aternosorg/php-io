<?php

namespace Aternos\IO\Test\Unit\System\Link;

use Aternos\IO\Exception\DeleteException;
use Aternos\IO\Exception\GetTargetException;
use Aternos\IO\Exception\IOException;
use Aternos\IO\System\FilesystemInterface;
use Aternos\IO\System\Link\Link;
use Aternos\IO\Test\Unit\System\FilesystemTestCase;
use ReflectionException;
use ReflectionObject;

class LinkTest extends FilesystemTestCase
{
    /**
     * @inheritDoc
     */
    protected function createElement(string $path): Link
    {
        return new Link($path);
    }

    protected function create(FilesystemInterface $element): void
    {
        symlink($this->getTmpPath() . "/test-target", $element->getPath());
        if (get_class($this) === LinkTest::class) {
            touch($this->getTmpPath() . "/test-target");
        }
        parent::create($element);
    }

    protected function assertExists(string $path): void
    {
        $this->assertTrue(is_link($path));
    }

    /**
     * @throws ReflectionException
     * @throws IOException
     */
    public function testThrowsExceptionOnImpossibleDelete(): void
    {
        $this->expectException(DeleteException::class);
        $this->expectExceptionMessage("Could not delete link (" . $this->getTmpPath() . "/test" . ")");
        $element = $this->createElement($this->getTmpPath() . "/test");
        (new ReflectionObject($element))->getProperty("existsOverride")->setValue($element, true);
        $element->delete();
    }

    /**
     * @return void
     * @throws GetTargetException
     * @throws IOException
     */
    public function testGetTargetTwiceReturnsSameObject(): void
    {
        touch($this->getTmpPath() . "/test-target");
        symlink($this->getTmpPath() . "/test-target", $this->getTmpPath() . "/test");
        $element = $this->createElement($this->getTmpPath() . "/test");
        $target = $element->getTarget();
        $this->assertSame($target, $element->getTarget());
    }

    /**
     * @return void
     * @throws GetTargetException
     * @throws IOException
     */
    public function testThrowsExceptionOnGetTargetWithMissingLink(): void
    {
        $this->expectException(GetTargetException::class);
        $this->expectExceptionMessage("Could not get link target because link does not exist (" . $this->getTmpPath() . "/test" . ")");
        $element = $this->createElement($this->getTmpPath() . "/test");
        $element->getTarget();
    }

    /**
     * @return void
     * @throws GetTargetException
     * @throws IOException
     */
    public function testThrowsExceptionOnGetTargetWithNoLink(): void
    {
        $this->expectException(GetTargetException::class);
        $this->expectExceptionMessage("Could not get link target because link does not exist (" . $this->getTmpPath() . "/test" . ")");
        $element = $this->createElement($this->getTmpPath() . "/test");
        touch($element->getPath());
        $element->getTarget();
    }

    public function testSerializeContainsTarget(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $serialized = $element->__serialize();
        $this->assertArrayHasKey("target", $serialized);
    }
}