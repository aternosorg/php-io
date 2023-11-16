<?php

namespace Aternos\IO\Test\Unit\Filesystem;

use PHPUnit\Framework\TestCase;

abstract class TmpDirTestCase extends TestCase
{
    protected ?string $tmpPath = null;

    protected function getTmpPath(): string
    {
        if ($this->tmpPath === null) {
            $this->tmpPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid("aternos-io-test-");
            mkdir($this->tmpPath);
        }
        return $this->tmpPath;
    }

    /**
     * @param string|null $path
     * @return void
     */
    protected function deleteTmpPath(?string $path = null): void
    {
        if ($path === null) {
            $path = $this->tmpPath;
        }
        if (!file_exists($path)) {
            return;
        }
        if (is_dir($path)) {
            if (!is_readable($path)) {
                chmod($path, 0777);
            }
            foreach (scandir($path) as $file) {
                if ($file === "." || $file === "..") {
                    continue;
                }
                $this->deleteTmpPath($path . DIRECTORY_SEPARATOR . $file);
            }
            rmdir($path);
        } else {
            unlink($path);
        }
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        $this->deleteTmpPath();
    }
}