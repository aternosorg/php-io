<?php

namespace Aternos\IO\Test\Unit\System\Directory;

use Aternos\IO\Exception\DeleteException;
use Aternos\IO\Exception\IOException;
use Aternos\IO\System\Directory\TempDirectory;
use PHPUnit\Framework\TestCase;

class TempDirectoryTest extends TestCase
{
    public function testSelectsPathForTemporaryFile(): void
    {
        $directory = new TempDirectory();
        $this->assertIsString($directory->getPath());
    }

    public function testSelectsPathWithPrefix(): void
    {
        $directory = new TempDirectory("test-");
        $this->assertStringStartsWith("test-", $directory->getName());
    }

    public function testCreatesDirectoryOnConstruct(): void
    {
        $directory = new TempDirectory();
        $this->assertDirectoryExists($directory->getPath());
    }

    public function testDeletesDirectoryOnDestruct(): void
    {
        $directory = new TempDirectory();
        $path = $directory->getPath();
        $this->assertDirectoryExists($path);
        unset($directory);
        $this->assertDirectoryDoesNotExist($path);
    }

    public function testDoesNotDeleteDirectoryOnDestruct(): void
    {
        $directory = new TempDirectory("test-", false);
        $path = $directory->getPath();
        $this->assertDirectoryExists($path);
        unset($directory);
        $this->assertDirectoryExists($path);
        rmdir($path);
    }

    /**
     * @throws IOException
     */
    public function testSerializeContainsDeleteOnDestruct(): void
    {
        $directory = new TempDirectory("test-", false);
        $serialized = $directory->__serialize();
        $this->assertArrayHasKey("deleteOnDestruct", $serialized);
        $directory->delete();
    }
}