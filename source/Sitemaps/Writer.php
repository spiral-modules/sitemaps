<?php

namespace Spiral\Sitemaps;

class Writer extends \XMLWriter
{
    /** @var File */
    private $file;

    /**
     * @param string $filename
     */
    public function __construct(string $filename)
    {
        $this->file = new File($filename);
    }

    public function getFilename(): string
    {
        return $this->file->getFilename();
    }

    /**
     * @param $resource
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
}