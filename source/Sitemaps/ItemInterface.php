<?php

namespace Spiral\Sitemaps;

interface ItemInterface
{
    /**
     * Render sitemap item data into string.
     *
     * @return string
     */
    public function render(): string;
}