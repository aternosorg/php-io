<?php

namespace Aternos\IO\System\Directory;

use Aternos\IO\Exception\CreateDirectoryException;
use Aternos\IO\Exception\DeleteException;
use Aternos\IO\Exception\GetTargetException;
use Aternos\IO\Exception\MissingPermissionsException;

/**
 * Class TempDirectory
 *
 * Temporary directory on disk, created automatically in the temp directory, deleted on destruct by default
 *
 * @package Aternos\IO\System\Directory
 */
class TempDirectory extends Directory
{
    /**
     * @param string $prefix Prefix for the temporary directory name
     * @param bool $deleteOnDestruct
     * @throws CreateDirectoryException
     */
    public function __construct(string $prefix = "io-", protected bool $deleteOnDestruct = true)
    {
        do {
            $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $prefix . uniqid();
        } while (file_exists($path));

        parent::__construct($path);

        $this->create();
    }

    /**
     * @throws DeleteException
     * @throws GetTargetException
     * @throws MissingPermissionsException
     */
    public function __destruct()
    {
        if ($this->deleteOnDestruct) {
            $this->delete();
        }
    }

    /**
     * @return array|string[]
     */
    public function __serialize(): array
    {
        return [
            ...parent::__serialize(),
            "deleteOnDestruct" => $this->deleteOnDestruct
        ];
    }
}