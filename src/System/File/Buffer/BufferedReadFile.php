<?php

namespace Aternos\IO\System\File\Buffer;

use Aternos\IO\Abstract\Buffer\Read\BufferedReadInterface;
use Aternos\IO\Abstract\Buffer\Read\BufferedReadSetPositionTrait;
use Aternos\IO\System\File\File;

/**
 * Class BufferedReadFile
 *
 * File class with buffered read capabilities
 *
 * @package Aternos\IO\Abstract\Buffer
 */
class BufferedReadFile extends File implements BufferedReadInterface
{
    use BufferedReadSetPositionTrait;
}