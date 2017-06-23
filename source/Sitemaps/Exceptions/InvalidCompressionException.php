<?php

namespace Spiral\Sitemaps\Exceptions;

class InvalidCompressionException extends \OutOfRangeException
{
    public function __construct($compression = "", $code = 0, \Throwable $previous = null)
    {
        $message = sprintf(
            'Unsupported compression rate "%s", valid values range is 1-9.',
            $compression
        );

        parent::__construct($message, $code, $previous);
    }
}