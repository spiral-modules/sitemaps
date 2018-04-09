<?php

namespace Spiral\Sitemaps\Writer;

class Buffer
{
    /** @var int */
    private $size = 0;

    /** @var int */
    private $elements = 0;

    /**
     * @param int $size
     */
    public function addElement(int $size)
    {
        $this->elements++;
        $this->size += $size;
    }

    public function flush()
    {
        $this->size = 0;
        $this->elements = 0;
    }

    /**
     * @return int
     */
    public function getElements(): int
    {
        return $this->elements;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }
}