<?php

namespace Spiral\Sitemaps\Exceptions;

class UnknownSitemapException extends \InvalidArgumentException
{
    public function __construct($sitemap = "", $code = 0, \Throwable $previous = null)
    {
        $message = sprintf('Unknown sitemap "%s".', $sitemap);

        parent::__construct($message, $code, $previous);
    }
}