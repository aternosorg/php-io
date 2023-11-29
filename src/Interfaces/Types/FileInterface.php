<?php

namespace Aternos\IO\Interfaces\Types;

use Aternos\IO\Interfaces\Features\CreateInterface;
use Aternos\IO\Interfaces\Features\DeleteInterface;
use Aternos\IO\Interfaces\Features\ExistsInterface;

interface FileInterface extends
    VolatileFileInterface,
    CreateInterface,
    ExistsInterface,
    DeleteInterface
{
}