<?php

namespace Aternos\IO\Interfaces\Types\Stream;

use Aternos\IO\Interfaces\Features\CloseInterface;
use Aternos\IO\Interfaces\Features\ReadInterface;

interface ReadStreamInterface extends ReadInterface, CloseInterface
{

}