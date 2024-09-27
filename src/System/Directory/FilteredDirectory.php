<?php

namespace Aternos\IO\System\Directory;

use Aternos\IO\Exception\PathOutsideElementException;
use Aternos\IO\System\Directory\Filter\PathFilter;
use Aternos\IO\System\FilesystemElement;
use Generator;

/**
 * Class FilteredDirectory
 *
 * Directory with filters for children
 *
 * @package Aternos\IO\System\Directory
 */
class FilteredDirectory extends Directory
{
    /**
     * @var PathFilter[]
     */
    protected array $filters = [];
    protected bool $mode = false;

    protected bool $skipFilter = false;

    /**
     * @param string $path
     * @param PathFilter[] $filters
     */
    public function __construct(string $path, array $filters = [])
    {
        parent::__construct($path);
        $this->filters = $filters;
    }

    /**
     * Add a filter to the directory
     *
     * @param PathFilter $filter
     * @return $this
     */
    public function addFilter(PathFilter $filter): static
    {
        $this->filters[] = $filter;
        return $this;
    }

    /**
     * Get current filters
     *
     * @return PathFilter[]
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * Set/replace all filters
     *
     * @param PathFilter[] $filters
     * @return $this
     */
    public function setFilters(array $filters): static
    {
        $this->filters = $filters;
        return $this;
    }

    /**
     * Set the filter mode to include only filtered files
     *
     * @return $this
     */
    public function includeOnlyFiltered(): static
    {
        $this->mode = true;
        return $this;
    }

    /**
     * Set the filter mode to exclude filtered files
     *
     * @return $this
     */
    public function excludeFiltered(): static
    {
        $this->mode = false;
        return $this;
    }

    /**
     * @inheritDoc
     * @throws PathOutsideElementException
     */
    public function getChildren(bool $allowOutsideLinks = false): Generator
    {
        foreach (parent::getChildren($allowOutsideLinks) as $child) {
            if ($this->shouldInclude($child) || $this->skipFilter) {
                yield $child;
            }
        }
    }

    /**
     * @inheritDoc
     * @throws PathOutsideElementException
     */
    public function getChildrenRecursive(bool $allowOutsideLinks = false, bool $followLinks = true, int $currentDepth = 0): Generator
    {
        $this->skipFilter = true;
        foreach (parent::getChildrenRecursive($allowOutsideLinks, $followLinks, $currentDepth) as $child) {
            if ($this->shouldInclude($child)) {
                yield $child;
            }
        }
        $this->skipFilter = false;
    }

    /**
     * Check if a filesystem element should be included based on the filters
     *
     * @param FilesystemElement $element
     * @return bool
     * @throws PathOutsideElementException
     */
    protected function shouldInclude(FilesystemElement $element): bool
    {
        foreach ($this->filters as $filter) {
            if ($filter->matches($element->getRelativePathTo($this))) {
                return $this->mode;
            }
        }
        return !$this->mode;
    }
}