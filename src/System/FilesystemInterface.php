<?php

namespace Aternos\IO\System;

use Aternos\IO\Interfaces\Features\DeleteInterface;
use Aternos\IO\Interfaces\Features\ExistsInterface;
use Aternos\IO\Interfaces\Features\GetAccessTimestampInterface;
use Aternos\IO\Interfaces\Features\GetModificationTimestampInterface;
use Aternos\IO\Interfaces\Features\GetStatusChangeTimestampInterface;
use Aternos\IO\Interfaces\Features\MovePathInterface;
use Aternos\IO\Interfaces\Features\SetAccessTimestampInterface;
use Aternos\IO\Interfaces\Features\SetModificationTimestampInterface;

/**
 * Interface FilesystemInterface
 *
 * Base interface for filesystem elements
 *
 * @package Aternos\IO\System
 */
interface FilesystemInterface extends
    MovePathInterface,
    DeleteInterface,
    ExistsInterface,
    GetAccessTimestampInterface,
    GetModificationTimestampInterface,
    GetStatusChangeTimestampInterface,
    SetAccessTimestampInterface,
    SetModificationTimestampInterface
{

}