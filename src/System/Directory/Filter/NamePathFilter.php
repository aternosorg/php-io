<?php

namespace Aternos\IO\System\Directory\Filter;

/**
 * Class NamePathFilter
 *
 * Path filter matching a name, e.g. a file or directory name
 *
 * Matches all files with the same name or in the given directory including the directory itself
 *
 * @package Aternos\IO\System\Directory\Filter
 */
class NamePathFilter extends PathFilter
{
    /**
     * @param string $name
     */
    public function __construct(protected string $name)
    {
    }

    /**
     * @inheritDoc
     */
    public function matches(string $path): bool
    {
        if ($path === $this->name) {
            return true;
        }

        if (!str_starts_with($path, $this->name)) {
            return false;
        }

        return $path[strlen($this->name)] === DIRECTORY_SEPARATOR;
    }
}