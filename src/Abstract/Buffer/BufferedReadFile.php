<?php

namespace Aternos\IO\Abstract\Buffer;

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