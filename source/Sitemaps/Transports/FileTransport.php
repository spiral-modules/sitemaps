<?php

namespace Spiral\Sitemaps\Transports;

use Spiral\Sitemaps\TransportInterface;
use Spiral\Sitemaps\Writer;

class FileTransport implements TransportInterface
{
    public function open(Writer $writer)
    {
        file_put_contents($writer->getFilename(), $writer->flush());
    }

    public function append(Writer $writer)
    {
        file_put_contents($writer->getFilename(), $writer->flush(), FILE_APPEND);
    }

    public function close(Writer $writer)
    {
        $this->append($writer);
    }
}