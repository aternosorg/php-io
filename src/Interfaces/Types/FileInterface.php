<?php

namespace Aternos\IO\Interfaces\Types;

use Aternos\IO\Interfaces\Features\CreateInterface;
use Aternos\IO\Interfaces\Features\DeleteInterface;
use Aternos\IO\Interfaces\Features\ExistsInterface;

/**
 * Interface FileInterface
 *
 * Interface for files
 *
 * @package Aternos\IO\Interfaces\Types
 */
interface FileInterface extends
    VolatileFileInterface,
    CreateInterface,
    ExistsInterface,
    DeleteInterface
{
}