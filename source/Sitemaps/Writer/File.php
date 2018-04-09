<?php

namespace Spiral\Sitemaps\Writer;

use Spiral\Sitemaps\Utils;

class File
{
    /** @var string */
    private $filename;

    /** @var resource|null */
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

    public function flushResource()
    {
        $file = clone $this;
        $file->resource = null;

        return $file;
    }

    /**
     * @return resource resource
     */
    public function getResource()
    {
        return $this->resource;
    }
}