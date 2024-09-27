<?php

namespace Aternos\IO\System\Directory\Filter;

/**
 * Class RegexPathFilter
 *
 * Path filter matching a regex pattern
 *
 * @package Aternos\IO\System\Directory\Filter
 */
class RegexPathFilter extends PathFilter
{
    /**
     * @param string $pattern
     */
    public function __construct(protected string $pattern)
    {
    }

    /**
     * @inheritDoc
     */
    public function matches(string $path): bool
    {
        return preg_match($this->pattern, $path) === 1;
    }
}