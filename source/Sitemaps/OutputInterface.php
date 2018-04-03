<?php

namespace Spiral\Sitemaps;

interface OutputInterface
{
    public function isFilename(): bool;

    public function getFilename(): string;

    public function isContent(): bool;

    public function getContent(): string;
}