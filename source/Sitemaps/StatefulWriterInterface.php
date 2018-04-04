<?php

namespace Spiral\Sitemaps;

use Spiral\Sitemaps\Writer\State;

interface StatefulWriterInterface
{
    public function getState(): State;
}