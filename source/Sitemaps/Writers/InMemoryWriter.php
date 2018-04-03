<?php

namespace Spiral\Sitemaps\Writers;

use Spiral\Sitemaps\Exceptions\Writers\WorkflowException;
use Spiral\Sitemaps\OutputInterface;
use Spiral\Sitemaps\Writer;

class InMemoryWriter extends AbstractWriter
{
    public function open()
    {
        if ($this->state->isOpened()) {
            throw new WorkflowException('XML writer is already opened.');
        }

        $this->state->open();

        $this->openMemory();
        $this->configure();
    }

    protected function output(): OutputInterface
    {
        return new Writer\Output\ContentOutput($this->outputMemory());
    }
}