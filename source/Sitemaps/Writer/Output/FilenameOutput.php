<?php

namespace Spiral\Sitemaps\Writer\Output;

use Spiral\Sitemaps\OutputInterface;

class FilenameOutput implements OutputInterface
{
    private $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function isFilename(): bool
    {
        return true;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function isContent(): bool
    {
        return false;
    }

    public function getContent(): string
    {
        throw new \DomainException('This is a filename output.');
    }

    public function __toString()
    {
        return $this->filename;
    }
}