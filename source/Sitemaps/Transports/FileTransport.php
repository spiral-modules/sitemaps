<?php

namespace Spiral\Sitemaps\Transports;

use Spiral\Sitemaps\TransportInterface;
use Spiral\Sitemaps\Writer;

class FileTransport implements TransportInterface
{
    public function open(Writer $writer)
    {
        $this->write($writer);
    }

    public function close(Writer $writer)
    {
        $this->write($writer, FILE_APPEND);
    }

    public function append(Writer $writer)
    {
        $this->write($writer, FILE_APPEND);
    }

    private function write(Writer $writer, $mode = 0)
    {
        file_put_contents($writer->getFilename(), $writer->flush(), $mode);
    }
}