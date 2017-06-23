<?php

namespace Spiral\Sitemaps\Exceptions;

class NotOpenedSitemapException extends \LogicException
{
    public function __construct($message = "", $code = 0, \Throwable $previous = null)
    {
        $message .= ' Sitemap is not opened.';

        parent::__construct($message, $code, $previous);
    }
}