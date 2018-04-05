<?php

namespace Spiral\Sitemaps\Transports;

use Spiral\Sitemaps\TransportInterface;
use Spiral\Sitemaps\Writer;

class GZIPTransport implements TransportInterface
{
    private $compression = 9;

    public function open(Writer $writer)
    {
        $handler = $this->initHandler($writer);
        gzwrite($handler, $writer->flush());
    }

    public function close(Writer $writer)
    {
        $handler = $this->writeAndReturnHandler($writer);
        gzclose($handler);
    }

    public function append(Writer $writer)
    {
        $this->writeAndReturnHandler($writer);
    }

    /**
     * @param Writer $writer
     *
     * @return resource
     */
    private function initHandler(Writer $writer)
    {
        $handler = gzopen($writer->getFilename() . '.gz', $this->getMode());
        $writer->setResource($handler);

        return $handler;
    }

    /**
     * @return string
     */
    private function getMode(): string
    {
        return 'wb' . $this->compression;
    }

    /**
     * @param Writer $writer
     *
     * @return resource
     */
    private function writeAndReturnHandler(Writer $writer)
    {
        $handler = $writer->getResource();
        gzwrite($handler, $writer->flush());

        return $handler;
    }
}