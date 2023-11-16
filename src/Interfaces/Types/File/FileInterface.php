<?php

namespace Aternos\IO\Interfaces\Types\File;

use Aternos\IO\Interfaces\Features\CloseInterface;
use Aternos\IO\Interfaces\Features\CreateInterface;
use Aternos\IO\Interfaces\Features\DeleteInterface;
use Aternos\IO\Interfaces\Features\ExistsInterface;
use Aternos\IO\Interfaces\Features\GetSizeInterface;
use Aternos\IO\Interfaces\Features\ReadInterface;
use Aternos\IO\Interfaces\Features\SetPositionInterface;
use Aternos\IO\Interfaces\IOElementInterface;

interface FileInterface extends
    IOElementInterface,
    SetPositionInterface,
    ReadInterface,
    CloseInterface,
    GetSizeInterface,
    CreateInterface,
    ExistsInterface,
    DeleteInterface
{
}