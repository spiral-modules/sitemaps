<?php

namespace Spiral\Sitemaps\Exceptions;

class InvalidPriorityException extends \OutOfRangeException
{
    public function __construct($priority = "", $code = 0, \Throwable $previous = null)
    {
        $message = sprintf(
            'Invalid sitemap priority "%s", valid values range is 0.0-1.0.',
            $priority
        );

        parent::__construct($message, $code, $previous);
    }
}