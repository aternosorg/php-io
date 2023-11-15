<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Interfaces\IOElementInterface;
use Generator;

interface GetChildrenInterface
{
    /**
     * @return Generator<IOElementInterface>
     */
    public function getChildren(): Generator;

    /**
     * @return Generator<IOElementInterface>
     */
    public function getChildrenRecursive(): Generator;
}