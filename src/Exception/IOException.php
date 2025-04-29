<?php

namespace Aternos\IO\Exception;

use Aternos\IO\Interfaces\IOElementInterface;
use Exception;
use Throwable;

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
     * @param Throwable|null $previous
     */
    public function __construct(
        string $message = "",
        protected IOElementInterface|null $element = null,
        ?Throwable $previous = null
    )
    {
        parent::__construct($message, previous: $previous);
    }

    /**
     * @return IOElementInterface|null
     */
    public function getIOElement(): ?IOElementInterface
    {
        return $this->element;
    }
}
