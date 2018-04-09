<?php

namespace Spiral\Sitemaps\Exceptions;

use Spiral\Sitemaps\SitemapsExceptionInterface;

class EnormousElementException extends \OverflowException implements SitemapsExceptionInterface
{
    public function __construct(string $size)
    {
        parent::__construct("Element is too big [$size] to be added to the sitemap.");
    }
}