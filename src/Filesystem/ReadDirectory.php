<?php

namespace Aternos\IO\Filesystem;

use Aternos\IO\Interfaces\IOElementInterface;

class ReadDirectory extends Directory
{
    /**
     * @param string $path
     * @return IOElementInterface
     */
    protected function createFile(string $path): IOElementInterface
    {
        return new ReadFile($path);
    }
}