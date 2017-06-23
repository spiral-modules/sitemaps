<?php

namespace Spiral\Sitemaps\Exceptions;

use Spiral\Sitemaps\Items\PageItem;

class InvalidFrequencyException extends \UnexpectedValueException
{
    public function __construct($frequency = "", $code = 0, \Throwable $previous = null)
    {
        $message = sprintf(
            'Invalid sitemap frequency "%s", valid values are: %s.',
            $frequency,
            join(', ', PageItem::FREQUENCIES)
        );

        parent::__construct($message, $code, $previous);
    }
}