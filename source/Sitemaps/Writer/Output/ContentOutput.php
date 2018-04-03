<?php

namespace Spiral\Sitemaps\Writer\Output;

use Spiral\Sitemaps\OutputInterface;

class ContentOutput implements OutputInterface
{
    private $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function isContent(): bool
    {
        return true;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function isFilename(): bool
    {
        return false;
    }

    public function getFilename(): string
    {
        throw new \DomainException('This is a content output.');
    }

    public function __toString()
    {
        return $this->content;
    }
}