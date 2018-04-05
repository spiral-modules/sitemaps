<?php

namespace Spiral\Sitemaps;

class File
{
    private $filename;

    /** @var resource */
    private $resource;

    /**
     * File constructor.
     *
     * @param string $filename
     */
    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @param resource $resource
     *
     * @return self
     */
    public function withResource($resource)
    {
        Utils::assertResource($resource);

        $file = clone $this;
        $file->resource = $resource;

        return $file;
    }

    /**
     * @return resource
     */
    public function getResource()
    {
        return $this->resource;
    }
}