<?php

namespace Aternos\IO\Test\Unit\Filesystem;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Exception\MissingPermissionsException;
use Aternos\IO\Exception\TruncateException;
use Aternos\IO\Exception\WriteException;
use Aternos\IO\Filesystem\ReadWriteFile;

class FileTest extends ReadFileTest
{
    protected function createElement(string $path): ReadWriteFile
    {
        return new ReadWriteFile($path);
    }

    /**
     * @throws IOException
     * @throws TruncateException
     */
    public function testTruncate(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        file_put_contents($path, "test");
        $element->truncate(2);
        $this->assertSame("te", file_get_contents($path));
    }

    /**
     * @throws IOException
     */
    public function testThrowsExceptionOnImpossibleTruncate(): void
    {
        $element = $this->createElement("/dev/null");
        $this->expectException(TruncateException::class);
        $element->truncate();
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
        $this->assertSame("test", file_get_contents($path));
    }

    /**
     * @throws IOException
     */
    public function testThrowsExceptionOnImpossibleWrite(): void
    {
        $path = $this->getTmpPath() . "/test";
        $element = $this->createElement($path);
        file_put_contents($path, "test");
        $reflectionObject = new \ReflectionObject($element);
        $reflectionObject->getProperty("fileResource")->setValue($element, fopen($path, "r"));
        $this->expectException(WriteException::class);
        $element->write("test");
    }

    /**
     * @throws WriteException
     * @throws IOException
     */
    public function testThrowsExceptionOnMissingWritePermissions(): void
    {
        $path = $this->getTmpPath() . "/test";
        file_put_contents($path, "test");
        chmod($path, 0444);
        $element = $this->createElement($path);
        $this->expectException(MissingPermissionsException::class);
        $element->write("test");
    }
}