<?php

namespace Spiral\Sitemaps\Writers;

use Spiral\Sitemaps\Compression;
use Spiral\Sitemaps\Compressor;
use Spiral\Sitemaps\Writer;

class PortionFileWriter extends FileWriter
{
    public function __construct(Writer\Configurator $configurator, Compression $compression)
    {
        parent::__construct($configurator);
    }

    /**
     * @param string          $filename
     * @param Compressor|null $compressor
     */
    public function open(string $filename, Compressor $compressor = null)
    {
        parent::open($filename);
    }

    public function writer()
    {
        return $this;
    }
}