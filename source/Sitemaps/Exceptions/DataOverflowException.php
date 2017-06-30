<?php

namespace Spiral\Sitemaps\Exceptions;

class DataOverflowException extends \OverflowException
{
    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        $message = 'Item size is too big, can\'t be added to sitemap.';
        parent::__construct($message, $code, $previous);
    }
}