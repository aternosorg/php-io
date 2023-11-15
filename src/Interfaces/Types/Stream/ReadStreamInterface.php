<?php

namespace Aternos\IO\Interfaces\Types\Stream;

use Aternos\IO\Interfaces\Features\CloseInterface;
use Aternos\IO\Interfaces\Features\ReadInterface;
use Aternos\IO\Interfaces\IOElementInterface;

interface ReadStreamInterface extends IOElementInterface, ReadInterface, CloseInterface
{

}