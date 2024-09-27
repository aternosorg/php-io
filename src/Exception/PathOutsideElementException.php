<?php

namespace Aternos\IO\Exception;

/**
 * Class PathOutsideElementException
 *
 * Thrown when a path outside the element is detected while trying to resolve a relative path
 * and paths outside the element are not allowed
 *
 * @package Aternos\IO\Exception
 */
class PathOutsideElementException extends IOException
{

}