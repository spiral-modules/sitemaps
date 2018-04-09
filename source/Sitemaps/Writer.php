<?php

namespace Spiral\Sitemaps;

class Writer extends \XMLWriter
{
    /** @var Writer\File */
    private $file;

    /** @var Writer\State */
    private $state;

    /** @var Writer\Buffer */
    private $buffer;

    /**
     * @param string $filename
     */
    public function __construct(string $filename)
    {
        $this->file = new Writer\File($filename);
        $this->state = new Writer\State();
        $this->buffer = new Writer\Buffer();
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->file->getFilename();
    }

    /**
     * @param resource $resource
     */
    public function setResource($resource)
    {
        $this->file = $this->file->withResource($resource);
    }

    public function flushResource()
    {
        $this->file = $this->file->flushResource();
    }

    /**
     * @return resource
     */
    public function getResource()
    {
        return $this->file->getResource();
    }

    public function bufferElements(): int
    {
        return $this->buffer->getElements();
    }

    public function bufferSize(): int
    {
        return $this->buffer->getSize();
    }

    public function logElement(int $size)
    {
        $this->state->addElement($size);
        $this->buffer->addElement($size);
    }

    public function flushBuffer()
    {
        $this->buffer->flush();
    }

    public function reserveSize(int $size)
    {
        $this->state->reserveSize($size);
    }

    public function getState(): Writer\State
    {
        return clone $this->state;
    }

}