<?php

namespace Spiral\Sitemaps\Writers;

use Spiral\Sitemaps\Exceptions\Writers\WorkflowException;
use Spiral\Sitemaps\OutputInterface;
use Spiral\Sitemaps\StatefulWriterInterface;
use Spiral\Sitemaps\Writer;

//todo just rewrite original xmlwriter open methods.
abstract class AbstractWriter extends \XMLWriter implements StatefulWriterInterface
{
    /** @var Writer\State */
    protected $state;

    /** @var Writer\Configurator */
    protected $configurator;

    /**
     * @param Writer\Configurator $configurator
     */
    public function __construct(Writer\Configurator $configurator)
    {
        $this->configurator = $configurator;
        $this->state = new Writer\State();
    }

    /**
     * @return Writer\State
     */
    public function getState(): Writer\State
    {
        return $this->state;
    }

    /**
     * @return Writer\Output\ContentOutput|OutputInterface
     * @throws WorkflowException
     */
    public function close(): OutputInterface
    {
        if ($this->state->isClosed()) {
            throw new WorkflowException('XML writer is already closed.');
        }

        $this->state->close();

        return $this->output();
    }

    protected function configure()
    {
        $this->configurator->configure($this);
    }

    /**
     * @return OutputInterface
     */
    abstract protected function output(): OutputInterface;
}