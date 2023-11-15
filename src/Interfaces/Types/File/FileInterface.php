<?php

namespace Aternos\IO\Interfaces\Types\File;

use Aternos\IO\Interfaces\Features\DeleteInterface;
use Aternos\IO\Interfaces\Features\TruncateInterface;
use Aternos\IO\Interfaces\Features\WriteInterface;

interface FileInterface extends
    ReadFileInterface,
    WriteInterface,
    DeleteInterface,
    TruncateInterface
{
}