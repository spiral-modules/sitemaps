<?php

namespace Spiral\Sitemaps\Writers;

use Spiral\Sitemaps\Exceptions\Writers\WorkflowException;
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
            throw new WorkflowException('XML writer is already opened.');
        }

        if (empty($filename)) {
            throw new WorkflowException('Filename is required.');
        }

        $this->state->open();
        $this->state->setFilename($filename);

        $this->openURI($filename);
        $this->configure();
    }

    protected function output(): OutputInterface
    {
        return new Writer\Output\FilenameOutput($this->state->getFilename());
    }
}