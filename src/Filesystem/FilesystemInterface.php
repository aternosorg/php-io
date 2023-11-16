<?php

namespace Aternos\IO\Filesystem;

use Aternos\IO\Interfaces\Features\CreateInterface;
use Aternos\IO\Interfaces\Features\DeleteInterface;
use Aternos\IO\Interfaces\Features\MovePathInterface;

interface FilesystemInterface extends MovePathInterface, CreateInterface, DeleteInterface
{

}