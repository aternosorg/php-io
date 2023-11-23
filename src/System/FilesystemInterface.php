<?php

namespace Aternos\IO\System;

use Aternos\IO\Interfaces\Features\DeleteInterface;
use Aternos\IO\Interfaces\Features\MovePathInterface;

interface FilesystemInterface extends MovePathInterface, DeleteInterface
{

}