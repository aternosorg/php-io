<?php

namespace Aternos\IO\Interfaces\Features;

use Aternos\IO\Exception\IOException;
use Aternos\IO\Interfaces\IOElementInterface;

interface GetChildInterface extends IOElementInterface
{
    /**
     * Get a child element that has the given features
     *
     * The child element does not have to exist (yet)
     * The child element might support additional features
     *
     * @param string $name
     * @param class-string<IOElementInterface>[] $features
     * @return IOElementInterface
     * @throws IOException
     */
    public function getChild(string $name, string ...$features): IOElementInterface;
}