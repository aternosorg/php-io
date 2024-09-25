<?php

namespace Aternos\IO\System\File;

class TempMemoryDiskFile extends TempMemoryFile
{
    /**
     * TempMemoryDiskFile constructor.
     *
     * @param int $memorySize The memory size in bytes. Default value is 2 * 1024 * 1024.
     * @return void
     */
    public function __construct(int $memorySize = 2 * 1024 * 1024)
    {
        /** @noinspection SpellCheckingInspection */
        $this->address = "php://temp/maxmemory:" . $memorySize;
    }

    public function getName(): ?string
    {
        return "memory disk";
    }
}