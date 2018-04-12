<?php

namespace Spiral\Sitemaps;

interface ElementInterface
{
    /**
     * @param \XMLWriter $writer
     */
    public function write(\XMLWriter $writer);
}