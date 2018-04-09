<?php

namespace Spiral\Sitemaps\Writer;

class State
{
    /** @var int */
    private $size = 0;

    /** @var int */
    private $reservedSize = 0;

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
    public function getCurrentSize(): int
    {
        return $this->size;
    }

    /**
     * @return int
     */
    public function getReservedSize(): int
    {
        return $this->reservedSize;
    }

    /**
     * @param int $size
     */
    public function addElement(int $size)
    {
        $this->elementsCount++;
        $this->size += $size;
    }

    /**
     * @param int $size
     */
    public function reserveSize(int $size)
    {
        $this->reservedSize += $size;
    }
}