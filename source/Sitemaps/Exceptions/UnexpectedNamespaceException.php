<?php

namespace Spiral\Sitemaps\Exceptions;

use Spiral\Sitemaps\SitemapsExceptionInterface;

class UnexpectedNamespaceException extends \UnexpectedValueException implements SitemapsExceptionInterface
{
}