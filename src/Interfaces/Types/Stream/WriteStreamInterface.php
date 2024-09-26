<?php

namespace Aternos\IO\Interfaces\Types\Stream;

use Aternos\IO\Interfaces\Features\CloseInterface;
use Aternos\IO\Interfaces\Features\WriteInterface;

interface WriteStreamInterface extends WriteInterface, CloseInterface
{

}