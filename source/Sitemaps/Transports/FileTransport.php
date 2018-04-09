<?php

namespace Spiral\Sitemaps\Transports;

use Spiral\Sitemaps\Configs\TransportConfig;
use Spiral\Sitemaps\TransportInterface;
use Spiral\Sitemaps\Writer;

class FileTransport implements TransportInterface
{
    private $config;

    public function __construct(TransportConfig $config)
    {
        $this->config = $config;
    }

    public function open(Writer $writer)
    {
        $handler = $this->initHandler($writer);
        fwrite($handler, $writer->flush());
    }

    public function close(Writer $writer)
    {
        $this->append($writer);
        fclose($writer->getResource());

        $writer->flushResource();
    }

    public function append(Writer $writer)
    {
        $handler = $writer->getResource();
        fwrite($handler, $writer->flush());
    }

    /**
     * @param Writer $writer
     *
     * @return resource
     */
    private function initHandler(Writer $writer)
    {
        $handler = fopen($writer->getFilename(), $this->config->getMode(static::class));
        $writer->setResource($handler);

        return $handler;
    }
}