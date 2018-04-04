<?php

namespace Spiral\Sitemaps\Exceptions\Writers;

class ForbiddenMethodCallException extends \DomainException
{
public function __construct()
{
    parent::__construct('This method call is forbidden in current implementation.');
}
}