<?php

namespace Spiral\Sitemaps\Exceptions;

class EnormousElementException extends \OverflowException
{
    public function __construct(string $size)
    {
        parent::__construct("Element is too big [$size] to be added to the sitemap.");
    }
}