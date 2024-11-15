<?php

namespace Aternos\IO\Test\Unit\System\Link;

use Aternos\IO\Exception\GetTargetException;
use Aternos\IO\Exception\IOException;
use Aternos\IO\Exception\StatException;
use Aternos\IO\Exception\TouchException;

class DeadLinkTest extends LinkTest
{
    /**
     * @throws GetTargetException
     * @throws IOException
     */
    public function testGetTargetPath(): void
    {
        symlink($this->getTmpPath() . "/test-target", $this->getTmpPath() . "/test");
        $element = $this->createElement($this->getTmpPath() . "/test");
        $this->assertEquals($this->getTmpPath() . "/test-target", $element->getTargetPath());
    }

    /**
     * @throws GetTargetException
     * @throws IOException
     */
    public function testGetFinalTargetPath(): void
    {
        symlink($this->getTmpPath() . "/test-target", $this->getTmpPath() . "/test1");
        symlink($this->getTmpPath() . "/test1", $this->getTmpPath() . "/test2");
        symlink($this->getTmpPath() . "/test2", $this->getTmpPath() . "/test3");
        $element = $this->createElement($this->getTmpPath() . "/test3");
        $this->assertEquals($this->getTmpPath() . "/test-target", $element->getFinalTargetPath());
    }

    /**
     * @throws IOException
     */
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

    /**
     * @throws IOException
     */
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

    /**
     * @throws IOException
     */
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

    /**
     * @throws IOException
     */
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

    /**
     * @return void
     * @throws GetTargetException
     * @throws IOException
     */
    public function testThrowsExceptionOnGetTargetWithMissingTarget(): void
    {
        $this->expectException(GetTargetException::class);
        $this->expectExceptionMessage("Could not get link target because target does not exist (" . $this->getTmpPath() . "/test-target" . ")");
        $element = $this->createElement($this->getTmpPath() . "/test");
        $this->create($element);
        $element->getTarget();
    }

    public function testGetModificationTimestamp(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $this->create($element);
        $this->expectException(StatException::class);
        /** @noinspection SpellCheckingInspection */
        $this->expectExceptionMessage("Could not get modification timestamp (" . $path . "): filemtime(): stat failed for " . $path);
        $element->getModificationTimestamp();
    }

    public function testGetAccessTimestamp(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $this->create($element);
        $this->expectException(StatException::class);
        /** @noinspection SpellCheckingInspection */
        $this->expectExceptionMessage("Could not get access timestamp (" . $path . "): fileatime(): stat failed for " . $path);
        $element->getAccessTimestamp();
    }

    public function testGetStatusChangeTimestamp(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $this->create($element);
        $this->expectException(StatException::class);
        /** @noinspection SpellCheckingInspection */
        $this->expectExceptionMessage("Could not get status change timestamp (" . $path . "): filectime(): stat failed for " . $path);
        $element->getStatusChangeTimestamp();
    }

    /**
     * @return void
     * @throws IOException
     * @throws StatException
     * @throws TouchException
     */
    public function testSetAccessTimestamp(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $this->create($element);
        $this->expectException(StatException::class);
        $this->expectExceptionMessage("Could not set access timestamp because element does not exist (" . $path . ")");
        $element->setAccessTimestamp(0);
    }

    /**
     * @return void
     * @throws IOException
     * @throws StatException
     * @throws TouchException
     */
    public function testSetModificationTimestamp(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $this->create($element);
        $this->expectException(StatException::class);
        $this->expectExceptionMessage("Could not set modification timestamp because element does not exist (" . $path . ")");
        $element->setModificationTimestamp(0);
    }
}