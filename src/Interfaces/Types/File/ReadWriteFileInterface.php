<?php

namespace Aternos\IO\Interfaces\Types\File;

use Aternos\IO\Interfaces\Features\TruncateInterface;
use Aternos\IO\Interfaces\Features\WriteInterface;

interface ReadWriteFileInterface extends
    ReadFileInterface,
    WriteInterface,
    TruncateInterface
{
}