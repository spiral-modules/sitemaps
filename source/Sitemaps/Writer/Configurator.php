<?php

namespace Spiral\Sitemaps\Writer;

use Spiral\Sitemaps\Configs\WriterConfig;

class Configurator
{
    private $config;

    public function __construct(WriterConfig $config)
    {
        $this->config = $config;
    }

    public function configure(\XMLWriter $writer): \XMLWriter
    {
        $writer->setIndent($this->config->indent());

        if ($this->config->indent()) {
            $writer->setIndentString($this->config->indentString());
        }

        return $writer;
    }
}