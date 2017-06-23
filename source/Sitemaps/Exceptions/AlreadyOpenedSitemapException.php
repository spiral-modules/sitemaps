<?php

namespace Spiral\Sitemaps\Exceptions;

class AlreadyOpenedSitemapException extends \LogicException
{
    public function __construct($message = "", $code = 0, \Throwable $previous = null)
    {
        $message .= ' Sitemap is already opened.';

        parent::__construct($message, $code, $previous);
    }
}