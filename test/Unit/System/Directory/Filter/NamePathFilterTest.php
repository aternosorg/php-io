<?php

namespace Aternos\IO\Test\Unit\System\Directory\Filter;

use Aternos\IO\System\Directory\Filter\NamePathFilter;
use PHPUnit\Framework\TestCase;

class NamePathFilterTest extends TestCase
{
    public function testMatchesSameName(): void
    {
        $filter = new NamePathFilter("test");
        $this->assertTrue($filter->matches("test"));
    }

    public function testMatchesSameNameWithTrailingSlash(): void
    {
        $filter = new NamePathFilter("test");
        $this->assertTrue($filter->matches("test/"));
    }

    public function testMatchesFileInDirectory(): void
    {
        $filter = new NamePathFilter("test");
        $this->assertTrue($filter->matches("test/file"));
    }

    public function testDoesNotMatchDifferentName(): void
    {
        $filter = new NamePathFilter("test");
        $this->assertFalse($filter->matches("not-test"));
    }

    public function testDoesNotMatchStartingWithSameName(): void
    {
        $filter = new NamePathFilter("test");
        $this->assertFalse($filter->matches("test-not"));
    }

    public function testMatchesFilterAndFileInDirectory(): void
    {
        $filter = new NamePathFilter("test/file");
        $this->assertTrue($filter->matches("test/file"));
    }
}