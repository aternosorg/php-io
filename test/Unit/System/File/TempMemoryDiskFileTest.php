<?php

namespace Aternos\IO\Test\Unit\System\File;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Exception\WriteException;
use Aternos\IO\System\File\TempMemoryDiskFile;

class TempMemoryDiskFileTest extends TempMemoryFileTest
{
    /**
     * @throws IOException
     * @throws WriteException
     */
    public function testWriteLargeFile(): void
    {
        $file = new TempMemoryDiskFile();
        $mebibyte = str_repeat("a", 1024 * 1024);
        for ($i = 0; $i < 256; $i++) {
            $file->write($mebibyte);
        }
        $this->assertEquals(256 * 1024 * 1024, $file->getSize());
    }
}