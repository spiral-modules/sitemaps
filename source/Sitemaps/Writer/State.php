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

    /** @var int */
    private $filesize = 0;

    private $reserverFilesize = 0;

    /** @var int */
    private $elementsCount = 0;

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

    /**
     * @return int
     */
    public function getCurrentElementsCount(): int
    {
        return $this->elementsCount;
    }

    /**
     * @return int
     */
    public function getCurrentFilesize(): int
    {
        return $this->filesize;
    }

    /**
     * @return int
     */
    public function getReservedFilesize(): int
    {
        return $this->filesize;
    }

    /**
     * @param int $filesize
     */
    public function addElement(int $filesize)
    {
        $this->elementsCount++;
        $this->filesize += $filesize;
    }

    /**
     * @param int $filesize
     */
    public function reserveFilesize(int $filesize)
    {
        $this->reserverFilesize += $filesize;
    }
}