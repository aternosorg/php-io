<?php

namespace Aternos\IO\Interfaces\Types\Stream;

use Aternos\IO\Interfaces\Features\CloseInterface;
use Aternos\IO\Interfaces\Features\ReadInterface;

/**
 * Interface ReadStreamInterface
 *
 * Interface for streams that can only be read from
 *
 * @package Aternos\IO\Interfaces\Types\Stream
 */
interface ReadStreamInterface extends ReadInterface, CloseInterface
{

}