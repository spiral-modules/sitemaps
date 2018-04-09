<?php

namespace Spiral\Sitemaps\Transports;

use Spiral\Sitemaps\Configs\TransportConfig;
use Spiral\Sitemaps\TransportInterface;
use Spiral\Sitemaps\Writer;

class GZIPTransport implements TransportInterface
{
    private $config;

    public function __construct(TransportConfig $config)
    {
        $this->config = $config;
    }

    public function open(Writer $writer)
    {
        $handler = $this->initHandler($writer);
        gzwrite($handler, $writer->flush());
    }

    public function close(Writer $writer)
    {
        $this->append($writer);
        gzclose($writer->getResource());

        $writer->flushResource();
    }

    public function append(Writer $writer)
    {
        $handler = $writer->getResource();
        gzwrite($handler, $writer->flush());
    }

    /**
     * @param Writer $writer
     *
     * @return resource
     */
    private function initHandler(Writer $writer)
    {
        $handler = gzopen($writer->getFilename() . '.gz', $this->config->getMode(static::class));
        $writer->setResource($handler);

        return $handler;
    }
}