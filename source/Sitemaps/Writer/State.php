<?php

namespace Spiral\Sitemaps\Writer;

class State
{
    /**
     * Allowed state values.
     */
    const OPENED = true;
    const CLOSED = false;

    /** @var bool */
    private $state = self::CLOSED;

    /** @var null|string */
    private $filename;

    /**
     * @return bool
     */
    public function isOpened(): bool
    {
        return $this->state === self::OPENED;
    }

    /**
     * @return bool
     */
    public function isClosed(): bool
    {
        return $this->state === self::CLOSED;
    }

    public function open()
    {
        $this->state = self::OPENED;
    }

    public function close()
    {
        $this->state = self::CLOSED;
    }

    /**
     * @param string $filename
     */
    public function setFilename(string $filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return null|string
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }
}