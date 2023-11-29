<?php

namespace Aternos\IO\Interfaces\Types;

use Aternos\IO\Interfaces\Features\CloseInterface;
use Aternos\IO\Interfaces\Features\GetSizeInterface;
use Aternos\IO\Interfaces\Features\IsEndOfFileInterface;
use Aternos\IO\Interfaces\Features\ReadInterface;
use Aternos\IO\Interfaces\Features\SetPositionInterface;
use Aternos\IO\Interfaces\Features\TruncateInterface;
use Aternos\IO\Interfaces\Features\WriteInterface;
use Aternos\IO\Interfaces\IOElementInterface;

interface VolatileFileInterface extends
    IOElementInterface,
    SetPositionInterface,
    IsEndOfFileInterface,
    ReadInterface,
    CloseInterface,
    GetSizeInterface,
    WriteInterface,
    TruncateInterface
{
}