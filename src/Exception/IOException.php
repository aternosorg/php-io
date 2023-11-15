<?php

namespace Aternos\IO\Exception;

use Aternos\IO\Interfaces\IOElementInterface;
use Exception;

class IOException extends Exception
{
    public function __construct(string $message = "", protected IOElementInterface|null $element = null)
    {
        parent::__construct($message);
    }

    /**
     * @return IOElementInterface|null
     */
    public function getIOElement(): ?IOElementInterface
    {
        return $this->element;
    }
}