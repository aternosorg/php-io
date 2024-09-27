<?php

namespace Aternos\IO\Interfaces\Types;

use Aternos\IO\Interfaces\Features\CreateInterface;
use Aternos\IO\Interfaces\Features\DeleteInterface;
use Aternos\IO\Interfaces\Features\ExistsInterface;
use Aternos\IO\Interfaces\Features\GetChildrenInterface;

/**
 * Interface DirectoryInterface
 *
 * Interface for directories
 *
 * @package Aternos\IO\Interfaces\Types
 */
interface DirectoryInterface extends
    DeleteInterface,
    GetChildrenInterface,
    CreateInterface,
    ExistsInterface
{
}