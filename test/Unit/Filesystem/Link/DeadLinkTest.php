<?php

namespace Aternos\IO\Test\Unit\Filesystem\Link;

use Aternos\IO\Exception\GetTargetException;

class DeadLinkTest extends LinkTest
{
    /**
     * @throws GetTargetException
     */
    public function testGetTargetPath(): void
    {
        symlink($this->getTmpPath() . "/test-target", $this->getTmpPath() . "/test");
        $element = $this->createElement($this->getTmpPath() . "/test");
        $this->assertEquals($this->getTmpPath() . "/test-target", $element->getTargetPath());
    }

    /**
     * @throws GetTargetException
     */
    public function testGetFinalTargetPath(): void
    {
        symlink($this->getTmpPath() . "/test-target", $this->getTmpPath() . "/test1");
        symlink($this->getTmpPath() . "/test1", $this->getTmpPath() . "/test2");
        symlink($this->getTmpPath() . "/test2", $this->getTmpPath() . "/test3");
        $element = $this->createElement($this->getTmpPath() . "/test3");
        $this->assertEquals($this->getTmpPath() . "/test-target", $element->getFinalTargetPath());
    }

    public function testThrowsExceptionOnGetFinalTargetWithInfiniteLinkLoop(): void
    {
        symlink($this->getTmpPath() . "/a", $this->getTmpPath() . "/b");
        symlink($this->getTmpPath() . "/b", $this->getTmpPath() . "/c");
        symlink($this->getTmpPath() . "/c", $this->getTmpPath() . "/a");
        $element = $this->createElement($this->getTmpPath() . "/c");
        $this->expectException(GetTargetException::class);
        $this->expectExceptionMessage("Could not get link target because of infinite link loop (" . $this->getTmpPath() . "/c" . ")");
        $element->getFinalTarget();
    }

    public function testThrowsExceptionOnGetFinalTargetPathWithInfiniteLinkLoop(): void
    {
        symlink($this->getTmpPath() . "/a", $this->getTmpPath() . "/b");
        symlink($this->getTmpPath() . "/b", $this->getTmpPath() . "/c");
        symlink($this->getTmpPath() . "/c", $this->getTmpPath() . "/a");
        $element = $this->createElement($this->getTmpPath() . "/c");
        $this->expectException(GetTargetException::class);
        $this->expectExceptionMessage("Could not get link target because of infinite link loop (" . $this->getTmpPath() . "/c" . ")");
        $element->getFinalTargetPath();
    }

    public function testThrowsExceptionOnGetFinalTargetWithTooManyLinks(): void
    {
        $path = $this->getTmpPath();
        for ($i = 0; $i < 41; $i++) {
            symlink($path . "/" . $i, $path . "/" . ($i + 1));
        }
        $element = $this->createElement($path . "/41");
        $this->expectException(GetTargetException::class);
        $this->expectExceptionMessage("Could not get link target because of too many levels of links (" . $path . "/41)");
        $element->getFinalTarget();
    }

    public function testThrowsExceptionOnGetFinalTargetPathWithTooManyLinks(): void
    {
        $path = $this->getTmpPath();
        for ($i = 0; $i < 41; $i++) {
            symlink($path . "/" . $i, $path . "/" . ($i + 1));
        }
        $element = $this->createElement($path . "/41");
        $this->expectException(GetTargetException::class);
        $this->expectExceptionMessage("Could not get link target because of too many levels of links (" . $path . "/41)");
        $element->getFinalTargetPath();
    }
}