<?php

namespace Aternos\IO\Interfaces\Types;

use Aternos\IO\Interfaces\Features\CloseInterface;
use Aternos\IO\Interfaces\Features\GetSizeInterface;
use Aternos\IO\Interfaces\Features\IsEndOfFileInterface;
use Aternos\IO\Interfaces\Features\ReadInterface;
use Aternos\IO\Interfaces\Features\SetPositionInterface;
use Aternos\IO\Interfaces\Features\TruncateInterface;
use Aternos\IO\Interfaces\Features\WriteInterface;

/**
 * Interface VolatileFileInterface
 *
 * Interface for volatile files, e.g. files in memory that cannot be created or deleted
 *
 * @package Aternos\IO\Interfaces\Types
 */
interface VolatileFileInterface extends
    SetPositionInterface,
    IsEndOfFileInterface,
    ReadInterface,
    CloseInterface,
    GetSizeInterface,
    WriteInterface,
    TruncateInterface
{
}