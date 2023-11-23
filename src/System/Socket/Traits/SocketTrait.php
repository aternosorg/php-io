<?php

namespace Aternos\IO\System\Socket\Traits;

use Aternos\IO\Exception\IOException;

trait SocketTrait
{
    /**
     * @var resource
     */
    protected mixed $socketResource = null;

    /**
     * @return bool
     */
    protected function hasSocketResource(): bool
    {
        return $this->socketResource !== null;
    }

    /**
     * @return resource
     * @throws IOException
     */
    protected function getSocketResource(): mixed
    {
        if (!$this->hasSocketResource()) {
            $this->socketResource = $this->openSocketResource();
        }
        return $this->socketResource;
    }

    /**
     * @return void
     */
    protected function clearSocketResource(): void
    {
        $this->socketResource = null;
    }

    /**
     * @param string $message
     * @param class-string<IOException> $type
     * @return void
     * @throws IOException
     */
    protected function throwException(string $message, string $type = IOException::class): void
    {
        $error = error_get_last();
        $message = str_replace("{type}", $this->getTypeForErrors(), $message);
        if (is_array($error) && isset($error["message"])) {
            $message .= ": " . $error["message"];
        }
        if ($context = $this->getErrorContext()) {
            $message .= " (" . $context . ")";
        }

        throw new $type($message, $this);
    }

    /**
     * @return string
     */
    abstract protected function getTypeForErrors(): string;

    /**
     * @return string|null
     */
    abstract protected function getErrorContext(): ?string;

    /**
     * @return resource
     */
    abstract protected function openSocketResource(): mixed;
}