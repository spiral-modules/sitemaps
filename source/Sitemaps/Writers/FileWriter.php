<?php

namespace Spiral\Sitemaps\Writers;

use Spiral\Sitemaps\Exceptions;
use Spiral\Sitemaps\OutputInterface;
use Spiral\Sitemaps\Writer;

class FileWriter extends AbstractWriter
{
    /**
     * @param string $filename
     */
    public function open(string $filename)
    {
        if ($this->state->isOpened()) {
            throw new Exceptions\Writers\WorkflowException('XML writer is already opened.');
        }

        $this->state->open();
        $this->state->setFilename($filename);

        $this->internalOpenURI($filename);
        $this->configure();
    }

    /**
     * @internal
     * @param string $uri
     *
     * @return bool|void
     */
    public function openURI($uri)
    {
        throw new Exceptions\Writers\ForbiddenMethodCallException();
    }

    protected function internalOpenURI($uri)
    {
        return parent::openURI($uri);
    }

    protected function output(): OutputInterface
    {
        return new Writer\Output\FilenameOutput($this->state->getFilename());
    }
}