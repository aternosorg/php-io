<?php

namespace Aternos\IO\System\File;

/**
 * Class TempMemoryDiskFile
 *
 * Temporary file in memory with a disk fallback, if the file gets above a certain size
 *
 * @package Aternos\IO\System\File
 */
class TempMemoryDiskFile extends TempMemoryFile
{
    /**
     * @param int $memorySize The memory size in bytes. Default value is 2 * 1024 * 1024. Creates a file on disk if the memory size is exceeded.
     * @return void
     */
    public function __construct(int $memorySize = 2 * 1024 * 1024)
    {
        /** @noinspection SpellCheckingInspection */
        $this->address = "php://temp/maxmemory:" . $memorySize;
    }

    /**
     * @inheritDoc
     */
    public function getName(): ?string
    {
        return "memory disk";
    }
}