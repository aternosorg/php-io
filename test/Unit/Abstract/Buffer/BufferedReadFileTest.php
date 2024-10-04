<?php

namespace Aternos\IO\Test\Unit\Abstract\Buffer;

use Aternos\IO\Abstract\Buffer\BufferedReadFile;
use Aternos\IO\Exception\IOException;
use Aternos\IO\Exception\ReadException;
use Aternos\IO\Exception\SeekException;
use Aternos\IO\Test\Unit\System\File\FileTest;

class BufferedReadFileTest extends FileTest
{
    /**
     * @inheritDoc
     */
    protected function createElement(string $path): BufferedReadFile
    {
        return new BufferedReadFile($path);
    }

    /**
     * @throws IOException
     */
    public function testReadIntoBuffer(): void
    {
        $path = $this->getTmpPath() . "/test";
        file_put_contents($path, "test");
        $element = $this->createElement($path);
        $element->readIntoBuffer(4);
        $this->assertEquals("test", $element->read(4));
    }

    /**
     * @throws IOException
     */
    public function testReadFromBufferAfterDeletion(): void
    {
        $path = $this->getTmpPath() . "/test";
        file_put_contents($path, "test");
        $element = $this->createElement($path);
        $element->readIntoBuffer(4);
        unlink($path);
        $this->assertEquals("test", $element->read(4));
    }

    /**
     * @throws IOException
     */
    public function testReadPartiallyFromBuffer(): void
    {
        $path = $this->getTmpPath() . "/test";
        file_put_contents($path, "test-test");
        $element = $this->createElement($path);
        $element->readIntoBuffer(4);
        $this->assertEquals("test-test", $element->read(9));
    }

    /**
     * @throws ReadException
     * @throws IOException
     */
    public function testReadPartiallyFromBufferAfterChange(): void
    {
        $path = $this->getTmpPath() . "/test";
        file_put_contents($path, "test-test");
        $element = $this->createElement($path);
        $element->readIntoBuffer(4);
        /** @noinspection SpellCheckingInspection */
        file_put_contents($path, "aaaa-test");
        $this->assertEquals("test-test", $element->read(9));
    }

    /**
     * @throws IOException
     * @throws SeekException
     * @throws ReadException
     */
    public function testSetPositionInBuffer(): void
    {
        $path = $this->getTmpPath() . "/test";
        file_put_contents($path, "0123456789");
        $element = $this->createElement($path);
        $element->readIntoBuffer(10);
        $element->setPosition(5);
        $this->assertEquals("5", $element->read(1));
    }

    /**
     * @throws IOException
     */
    public function testAutomaticReadBuffer(): void
    {
        $path = $this->getTmpPath() . "/test";
        file_put_contents($path, "0123456789");
        $element = $this->createElement($path);
        $element->setAutomaticReadBufferLength(5);
        $this->assertEquals("01234", $element->read(5));
        $this->assertEquals("56789", $element->read(5));
    }

    /**
     * @throws IOException
     */
    public function testReadFromAutomaticBufferAfterDeletion(): void
    {
        $path = $this->getTmpPath() . "/test";
        file_put_contents($path, "0123456789");
        $element = $this->createElement($path);
        $element->setAutomaticReadBufferLength(10);
        $this->assertEquals("01234", $element->read(5));
        unlink($path);
        $this->assertEquals("56789", $element->read(5));
    }

    /**
     * @return void
     * @throws IOException
     * @throws ReadException
     */
    public function testClearReadBuffer(): void
    {
        $path = $this->getTmpPath() . "/test";
        file_put_contents($path, "test");
        $element = $this->createElement($path);
        $element->readIntoBuffer(4);
        $element->clearReadBuffer();
        $this->assertEquals("test", $element->read(4));
    }

    /**
     * @return void
     * @throws IOException
     * @throws ReadException
     */
    public function testThrowsExceptionAfterChangeAndClearReadBuffer(): void
    {
        $path = $this->getTmpPath() . "/test";
        file_put_contents($path, "0123456789");
        $element = $this->createElement($path);
        $element->readIntoBuffer(10);
        $this->assertEquals("01234", $element->read(5));
        file_put_contents($path, "");
        $element->clearReadBuffer();
        $this->assertEquals("", $element->read(5));
    }
}