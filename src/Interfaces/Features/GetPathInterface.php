<?php

namespace Aternos\IO\Interfaces\Features;

interface GetPathInterface extends GetNameInterface
{
    /**
     * @return string
     */
    public function getPath(): string;

    /**
     * @param GetPathInterface $element
     * @param bool $allowOutsideElement
     * @return string
     */
    public function getRelativePathTo(GetPathInterface $element, bool $allowOutsideElement = false): string;
}