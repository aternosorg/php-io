<?php

namespace Aternos\IO\Filesystem;

use Aternos\IO\Exception\CreateDirectoryException;
use Aternos\IO\Interfaces\Types\DirectoryInterface;
use Generator;

class Directory extends FilesystemElement implements DirectoryInterface
{
    public function delete(): static
    {
        // TODO: Implement delete() method.
    }

    public function getChildren(): Generator
    {
        // TODO: Implement getChildren() method.
    }

    public function getChildrenRecursive(): Generator
    {
        // TODO: Implement getChildrenRecursive() method.
    }

    /**
     * @throws CreateDirectoryException
     */
    public function create(): static
    {
        if(!@mkdir($this->path, recursive: true)) {
            $error = error_get_last();
            throw new CreateDirectoryException("Could not create directory (" . $this->path . ")" . ($error ? ": " . $error["message"] : ""), $this);
        }
        return $this;
    }
}