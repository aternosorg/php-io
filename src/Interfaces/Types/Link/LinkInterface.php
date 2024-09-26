<?php

namespace Aternos\IO\Interfaces\Types\Link;

use Aternos\IO\Interfaces\Features\ExistsInterface;
use Aternos\IO\Interfaces\Features\GetTargetInterface;
use Aternos\IO\Interfaces\Features\SetTargetInterface;
use Aternos\IO\Interfaces\Features\TargetExistsInterface;

interface LinkInterface extends ExistsInterface, GetTargetInterface, TargetExistsInterface, SetTargetInterface
{

}