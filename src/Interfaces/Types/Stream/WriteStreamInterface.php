<?php

namespace Aternos\IO\Interfaces\Types\Stream;

use Aternos\IO\Interfaces\Features\CloseInterface;
use Aternos\IO\Interfaces\Features\WriteInterface;
use Aternos\IO\Interfaces\IOElementInterface;

interface WriteStreamInterface extends IOElementInterface, WriteInterface, CloseInterface
{

}