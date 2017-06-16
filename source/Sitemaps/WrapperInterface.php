<?php

namespace Spiral\Sitemaps;

interface WrapperInterface
{
    /**
     * Header open tag.
     *
     * @return string
     */
    public function header(): string;

    /**
     * Footer close tag.
     *
     * @return string
     */
    public function footer(): string;
}