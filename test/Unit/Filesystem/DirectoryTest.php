<?php

namespace Aternos\IO\Test\Unit\Filesystem;

use Aternos\IO\Filesystem\Directory;
use Aternos\IO\Filesystem\FilesystemElement;

class DirectoryTest extends FilesystemTestCase
{
    protected function createElement(string $path): FilesystemElement
    {
        return new Directory($path);
    }
}