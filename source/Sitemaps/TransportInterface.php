<?php

namespace Spiral\Sitemaps;

interface TransportInterface
{
    public function open(Writer $writer);

    public function append(Writer $writer);

    public function close(Writer $writer);
}