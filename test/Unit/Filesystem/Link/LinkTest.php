<?php

namespace Aternos\IO\Test\Unit\Filesystem\Link;

use Aternos\IO\Exception\DeleteException;
use Aternos\IO\Exception\GetTargetException;
use Aternos\IO\Filesystem\FilesystemInterface;
use Aternos\IO\Filesystem\Link\Link;
use Aternos\IO\Test\Unit\Filesystem\FilesystemTestCase;
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
    }

    protected function assertExists(string $path): void
    {
        $this->assertTrue(is_link($path));
    }

    /**
     * @throws ReflectionException
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
     */
    public function testThrowsExceptionOnGetTargetWithMissingLink(): void
    {
        $this->expectException(GetTargetException::class);
        $this->expectExceptionMessage("Could not get link target because link does not exist (" . $this->getTmpPath() . "/test" . ")");
        $element = $this->createElement($this->getTmpPath() . "/test");
        $element->getTarget();
    }

    public function testThrowsExceptionOnGetTargetWithNoLink(): void
    {
        $this->expectException(GetTargetException::class);
        $this->expectExceptionMessage("Could not get link target because link does not exist (" . $this->getTmpPath() . "/test" . ")");
        $element = $this->createElement($this->getTmpPath() . "/test");
        touch($element->getPath());
        $element->getTarget();
    }

    /**
     * @return void
     * @throws GetTargetException
     */
    public function testThrowsExceptionOnGetTargetWithMissingTarget(): void
    {
        $this->expectException(GetTargetException::class);
        $this->expectExceptionMessage("Could not get link target because target does not exist (" . $this->getTmpPath() . "/test-target" . ")");
        $element = $this->createElement($this->getTmpPath() . "/test");
        $this->create($element);
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