<?php

namespace Aternos\IO\System\Directory\Filter;

/**
 * Class PathFilter
 *
 * Base class for path filters
 *
 * @package Aternos\IO\System\Directory\Filter
 */
abstract class PathFilter
{
    /**
     * Check if the path matches the filter
     *
     * @param string $path
     * @return bool
     */
    abstract public function matches(string $path): bool;
}