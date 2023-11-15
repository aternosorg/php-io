<?php

namespace Aternos\IO\Test\Unit\Filesystem;


use Aternos\IO\Filesystem\FilesystemElement;
use Aternos\IO\Filesystem\ReadFile;

class ReadFileTest extends FilesystemTestCase
{
    protected function createElement(string $path): FilesystemElement
    {
        return new ReadFile($path);
    }
}