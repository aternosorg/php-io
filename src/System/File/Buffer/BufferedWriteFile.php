<?php

namespace Aternos\IO\System\File\Buffer;

use Aternos\IO\Abstract\Buffer\Write\BufferedWriteInterface;
use Aternos\IO\Abstract\Buffer\Write\BufferedWriteSetPositionTrait;
use Aternos\IO\System\File\File;

/**
 * Class BufferedWriteFile
 *
 * File class with buffered write capabilities
 *
 * @package Aternos\IO\System\File\Buffer
 */
class BufferedWriteFile extends File implements BufferedWriteInterface
{
    use BufferedWriteSetPositionTrait;
}