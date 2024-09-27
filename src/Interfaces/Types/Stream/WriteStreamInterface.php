<?php

namespace Aternos\IO\Interfaces\Types\Stream;

use Aternos\IO\Interfaces\Features\CloseInterface;
use Aternos\IO\Interfaces\Features\WriteInterface;

/**
 * Interface WriteStreamInterface
 *
 * Interface for streams that can only be written to
 *
 * @package Aternos\IO\Interfaces\Types\Stream
 */
interface WriteStreamInterface extends WriteInterface, CloseInterface
{

}