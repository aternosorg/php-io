<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Interfaces\IOElementInterface;
use Generator;

interface GetChildrenInterface extends IOElementInterface
{
    /**
     * @return Generator<IOElementInterface>
     */
    public function getChildren(bool $allowOutsideLinks = false): Generator;

    /**
     * @return Generator<IOElementInterface>
     */
    public function getChildrenRecursive(bool $allowOutsideLinks = false, bool $followLinks = true, int $currentDepth = 0): Generator;
}