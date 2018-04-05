<?php

namespace Spiral\Sitemaps\Writer;

class State
{
    /** @var int */
    private $filesize = 0;

    /** @var int */
    private $reservedFilesize = 0;

    /** @var int */
    private $elementsCount = 0;

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
        return $this->reservedFilesize;
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
        $this->reservedFilesize += $filesize;
    }
}