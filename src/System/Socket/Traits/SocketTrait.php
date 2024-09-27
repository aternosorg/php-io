<?php

namespace Aternos\IO\System\Socket\Traits;

use Aternos\IO\Exception\IOException;

/**
 * Trait SocketTrait
 *
 * Base trait for all other socket traits
 *
 * @package Aternos\IO\System\Socket\Traits
 */
trait SocketTrait
{
    /**
     * @var resource
     */
    protected mixed $socketResource = null;

    /**
     * Check if a socket resource is available
     *
     * @return bool
     */
    protected function hasSocketResource(): bool
    {
        return $this->socketResource !== null;
    }

    /**
     * Get the socket resource
     *
     * @return resource
     */
    abstract protected function getSocketResource(): mixed;

    /**
     * Unset the socket resource
     *
     * @return void
     */
    protected function clearSocketResource(): void
    {
        $this->socketResource = null;
    }

    /**
     * Throw an exception with the given message
     *
     * Includes type name and error context
     *
     * @template T of IOException
     * @param string $message
     * @param class-string<T> $type
     * @return void
     * @throws T
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
     * Get the type name for error messages
     *
     * @return string
     */
    abstract protected function getTypeForErrors(): string;

    /**
     * Get the error context for error messages, e.g. the path
     *
     * @return string|null
     */
    abstract protected function getErrorContext(): ?string;
}