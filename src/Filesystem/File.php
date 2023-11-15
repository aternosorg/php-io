<?php

namespace Aternos\IO\Filesystem;

use Aternos\IO\Interfaces\Types\File\FileInterface;

class File extends ReadFile implements FileInterface
{
    protected string $mode = "c+b";

    public function delete(): static
    {
        // TODO: Implement delete() method.
    }

    public function truncate(int $size = 0): static
    {
        // TODO: Implement truncate() method.
    }

    public function write(string $buffer): static
    {
        // TODO: Implement write() method.
    }
}