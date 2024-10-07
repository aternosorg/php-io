<?php

namespace Aternos\IO\Test\Unit\System\File\Buffer;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Exception\SeekException;
use Aternos\IO\Exception\WriteException;
use Aternos\IO\System\File\Buffer\BufferedWriteFile;
use Aternos\IO\Test\Unit\System\File\FileTest;

class BufferedWriteFileTest extends FileTest
{
    /**
     * @inheritDoc
     */
    protected function createElement(string $path): BufferedWriteFile
    {
        return new BufferedWriteFile($path);
    }

    /**
     * @throws IOException
     * @throws WriteException
     */
    public function testWrite(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $element->write("test");
        unset($element);
        $this->assertSame("test", file_get_contents($path));
    }

    /**
     * @return void
     * @throws IOException
     * @throws WriteException
     */
    public function testWriteToBuffer(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $element->write("test");
        $this->assertSame("", file_get_contents($path));
    }

    /**
     * @return void
     * @throws IOException
     * @throws WriteException
     */
    public function testGetPositionInBuffer(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $element->write("test");
        $this->assertSame(4, $element->getPosition());
    }

    /**
     * @return void
     * @throws IOException
     * @throws WriteException
     */
    public function testFlushWriteBuffer(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $element->write("test");
        $this->assertSame("", file_get_contents($path));
        $element->flushWriteBuffer();
        $this->assertSame("test", file_get_contents($path));
    }

    /**
     * @return void
     * @throws IOException
     * @throws WriteException
     */
    public function testFlushesBufferOnDestruct(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $element->write("test");
        unset($element);
        $this->assertSame("test", file_get_contents($path));
    }

    /**
     * @return void
     * @throws IOException
     * @throws WriteException
     * @throws SeekException
     */
    public function testOverwriteBuffer(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $element->write("123456");
        $element->setPosition(3);
        $element->write("789");
        $element->flushWriteBuffer();
        $this->assertSame("123789", file_get_contents($path));
    }

    /**
     * @return void
     * @throws IOException
     * @throws SeekException
     * @throws WriteException
     */
    public function testFlushesBufferOnSetPositionOutsideBuffer(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $element->write("123456");
        $this->assertEquals("", file_get_contents($path));
        $element->flushWriteBuffer();
        $element->write("789");
        $this->assertSame("123456", file_get_contents($path));
        $element->setPosition(3);
        $this->assertSame("123456789", file_get_contents($path));
    }

    /**
     * @return void
     * @throws IOException
     * @throws SeekException
     * @throws WriteException
     */
    public function testDoesNotFlushBufferOnSetPositionInsideBuffer(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $element->write("123456");
        $this->assertSame("", file_get_contents($path));
        $element->setPosition(3);
        $element->write("789");
        $this->assertSame("", file_get_contents($path));
        $element->flushWriteBuffer();
        $this->assertSame("123789", file_get_contents($path));
    }

    /**
     * @return void
     * @throws IOException
     * @throws WriteException
     */
    public function testFlushesBufferWhenMaxLengthIsReached(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $element->setMaxWriteBufferLength(3);
        $element->write("123");
        $this->assertSame("", file_get_contents($path));
        $element->write("456");
        $this->assertSame("123456", file_get_contents($path));
        $element->write("789");
        $this->assertSame("123456", file_get_contents($path));
        $element->flushWriteBuffer();
        $this->assertSame("123456789", file_get_contents($path));
    }

    /**
     * @return void
     * @throws IOException
     * @throws WriteException
     */
    public function testFlushesBufferWhenPositionIsOutside(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        $element->write("123");
        $element->flushWriteBuffer();
        $element->write("456");

        $reflectionObject = new \ReflectionObject($element);
        $reflectionProperty = $reflectionObject->getProperty("writeBuffer");
        $reflectionProperty->getValue($element)->setPosition(10);

        $element->write("789");
        $this->assertSame("123456", file_get_contents($path));
    }
}