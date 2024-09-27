<?php

namespace Aternos\IO\Interfaces\Types\Link;

use Aternos\IO\Interfaces\Features\ExistsInterface;
use Aternos\IO\Interfaces\Features\GetTargetInterface;
use Aternos\IO\Interfaces\Features\SetTargetInterface;
use Aternos\IO\Interfaces\Features\TargetExistsInterface;

/**
 * Interface LinkInterface
 *
 * Interface for links with all target features
 *
 * @package Aternos\IO\Interfaces\Types\Link
 */
interface LinkInterface extends ExistsInterface, GetTargetInterface, TargetExistsInterface, SetTargetInterface
{

}