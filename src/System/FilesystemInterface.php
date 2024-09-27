<?php

namespace Aternos\IO\System;

use Aternos\IO\Interfaces\Features\DeleteInterface;
use Aternos\IO\Interfaces\Features\ExistsInterface;
use Aternos\IO\Interfaces\Features\MovePathInterface;

/**
 * Interface FilesystemInterface
 *
 * Base interface for filesystem elements
 *
 * @package Aternos\IO\System
 */
interface FilesystemInterface extends MovePathInterface, DeleteInterface, ExistsInterface
{

}