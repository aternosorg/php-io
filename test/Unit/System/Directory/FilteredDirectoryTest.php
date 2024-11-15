<?php

namespace Aternos\IO\Test\Unit\System\Directory;

use Aternos\IO\Exception\GetTargetException;
use Aternos\IO\Exception\IOException;
use Aternos\IO\Exception\MissingPermissionsException;
use Aternos\IO\Exception\PathOutsideElementException;
use Aternos\IO\System\Directory\Directory;
use Aternos\IO\System\Directory\Filter\NamePathFilter;
use Aternos\IO\System\Directory\FilteredDirectory;
use Aternos\IO\System\File\File;

class FilteredDirectoryTest extends DirectoryTest
{
    protected function createElement(string $path): FilteredDirectory
    {
        return new FilteredDirectory($path);
    }

    public function testSetConstructorFilters(): void
    {
        $filters = [new NamePathFilter("test")];
        $directory = new FilteredDirectory($this->getTmpPath(), $filters);
        $this->assertEquals($filters, $directory->getFilters());
    }

    public function testSetFilters(): void
    {
        $filters = [new NamePathFilter("test")];
        $directory = $this->createElement($this->getTmpPath());
        $directory->setFilters($filters);
        $this->assertEquals($filters, $directory->getFilters());
    }

    public function testAddFilter(): void
    {
        $filter = new NamePathFilter("test");
        $directory = $this->createElement($this->getTmpPath());
        $directory->addFilter($filter);
        $this->assertEquals([$filter], $directory->getFilters());
    }

    public function testGetNoFilters(): void
    {
        $directory = $this->createElement($this->getTmpPath());
        $this->assertEquals([], $directory->getFilters());
    }

    /**
     * @throws GetTargetException
     * @throws MissingPermissionsException
     * @throws PathOutsideElementException
     * @throws IOException
     */
    public function testExcludesFiltered(): void
    {
        $path = $this->getTmpPath();
        touch($path . "/test");
        touch($path . "/test2");

        $directory = new FilteredDirectory($path, [new NamePathFilter("test")]);
        $children = iterator_to_array($directory->getChildren());
        $this->assertCount(1, $children);

        $this->assertPathHasTypeInArray($path . "/test2", File::class, $children);
    }

    /**
     * @throws GetTargetException
     * @throws MissingPermissionsException
     * @throws PathOutsideElementException
     * @throws IOException
     */
    public function testExcludesFilteredExplicit(): void
    {
        $path = $this->getTmpPath();
        touch($path . "/test");
        touch($path . "/test2");

        $directory = new FilteredDirectory($path, [new NamePathFilter("test")]);
        $directory->excludeFiltered();
        $children = iterator_to_array($directory->getChildren());
        $this->assertCount(1, $children);

        $this->assertPathHasTypeInArray($path . "/test2", File::class, $children);
    }

    /**
     * @throws GetTargetException
     * @throws MissingPermissionsException
     * @throws PathOutsideElementException
     * @throws IOException
     */
    public function testExcludesFilteredRecursive(): void
    {
        $path = $this->getTmpPath();
        mkdir($path . "/test");
        mkdir($path . "/test/test");
        touch($path . "/test/test/test");
        touch($path . "/test2");

        $directory = new FilteredDirectory($path, [new NamePathFilter("test/test")]);
        $children = iterator_to_array($directory->getChildren());
        $this->assertCount(2, $children);

        $this->assertPathHasTypeInArray($path . "/test2", File::class, $children);
        $this->assertPathHasTypeInArray($path . "/test", Directory::class, $children);
    }

    /**
     * @throws GetTargetException
     * @throws MissingPermissionsException
     * @throws PathOutsideElementException
     * @throws IOException
     */
    public function testIncludesOnlyFiltered(): void
    {
        $path = $this->getTmpPath();
        touch($path . "/test");
        touch($path . "/test2");

        $directory = new FilteredDirectory($path, [new NamePathFilter("test2")]);
        $directory->includeOnlyFiltered();
        $children = iterator_to_array($directory->getChildren());
        $this->assertCount(1, $children);

        $this->assertPathHasTypeInArray($path . "/test2", File::class, $children);
    }

    /**
     * @throws GetTargetException
     * @throws MissingPermissionsException
     * @throws PathOutsideElementException
     * @throws IOException
     */
    public function testIncludesOnlyFilteredRecursive(): void
    {
        $path = $this->getTmpPath();
        mkdir($path . "/test");
        mkdir($path . "/test/test");
        touch($path . "/test/test/test");
        touch($path . "/test2");

        $directory = new FilteredDirectory($path, [new NamePathFilter("test/test")]);
        $directory->includeOnlyFiltered();
        $children = iterator_to_array($directory->getChildrenRecursive());
        $this->assertCount(2, $children);

        $this->assertPathHasTypeInArray($path . "/test/test", Directory::class, $children);
        $this->assertPathHasTypeInArray($path . "/test/test/test", File::class, $children);
    }
}