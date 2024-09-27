<?php

namespace Aternos\IO\Test\Unit\System\Directory\Filter;

use Aternos\IO\System\Directory\Filter\RegexPathFilter;
use PHPUnit\Framework\TestCase;

class RegexPathFilterTest extends TestCase
{
    public function testMatches(): void
    {
        $filter = new RegexPathFilter("/^t\wst$/");
        $this->assertTrue($filter->matches("test"));
    }

    public function testDoesNotMatch(): void
    {
        $filter = new RegexPathFilter("/^t\wst$/");
        $this->assertFalse($filter->matches("not-test"));
    }
}