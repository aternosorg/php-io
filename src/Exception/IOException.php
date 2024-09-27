<?php

namespace Aternos\IO\Exception;

use Aternos\IO\Interfaces\IOElementInterface;
use Exception;

/**
 * Class IOException
 *
 * Base class for all IO exceptions
 *
 * @package Aternos\IO\Exception
 */
class IOException extends Exception
{
    /**
     * @param string $message
     * @param IOElementInterface|null $element
     */
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