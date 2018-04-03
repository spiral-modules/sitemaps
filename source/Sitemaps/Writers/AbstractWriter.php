<?php

namespace Spiral\Sitemaps\Writers;

use Spiral\Sitemaps\Exceptions\Writers\WorkflowException;
use Spiral\Sitemaps\OutputInterface;
use Spiral\Sitemaps\Writer;

abstract class AbstractWriter extends \XMLWriter
{
    /** @var Writer\State */
    public $state;

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