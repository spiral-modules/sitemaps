<?php

namespace Spiral\Sitemaps\Builders;

use Spiral\Sitemaps\Configs\BuilderConfig;
use Spiral\Sitemaps\Configurator;
use Spiral\Sitemaps\Declaration;
use Spiral\Sitemaps\ElementInterface;
use Spiral\Sitemaps\Exceptions\EnormousElementException;
use Spiral\Sitemaps\Exceptions\WorkflowException;
use Spiral\Sitemaps\Reservation;
use Spiral\Sitemaps\TransportInterface;
use Spiral\Sitemaps\Utils;
use Spiral\Sitemaps\Validators\SitemapValidator;
use Spiral\Sitemaps\Writer;

abstract class AbstractBuilder
{
    /** @var SitemapValidator */
    private $validator;

    /** @var \Spiral\Sitemaps\Declaration */
    protected $declaration;

    /** @var Reservation */
    private $reservation;

    /** @var Configurator */
    private $configurator;

    /** @var BuilderConfig */
    private $config;

    /** @var Writer|null */
    protected $writer;

    /** @var Writer|null */
    protected $writerHelper;

    /** @var TransportInterface|null */
    protected $transport;

    /** @var Writer\State */
    protected $state;

    /** @var Writer\Buffer */
    protected $buffer;

    public function __construct(
        Declaration $declaration,
        SitemapValidator $validator,
        Reservation $reservation,
        Configurator $configurator,
        BuilderConfig $config
    ) {
        $this->declaration = $declaration;
        $this->validator = $validator;
        $this->reservation = $reservation;
        $this->configurator = $configurator;
        $this->config = $config;
    }

    /**
     * @param TransportInterface $transport
     * @param string             $filename
     * @param array              $namespaces
     */
    public function start(TransportInterface $transport, string $filename, array $namespaces = [])
    {
        if (!empty($this->writer)) {
            throw new WorkflowException('XML writer is already opened.');
        }

        $this->state = new Writer\State();
        $this->buffer = new Writer\Buffer();
        $this->transport = $transport;

        $this->state->reserveFilesize($this->reservation->calculateSize(static::class, $namespaces));
        $this->writer = $this->makeConfiguredWriter($filename, $namespaces);
        $this->writerHelper = $this->makeWriterHelper($namespaces);

        $this->transport->open($this->writer);
    }

    public function end()
    {
        if (empty($this->writer)) {
            throw new WorkflowException('XML writer is already closed.');
        }

        $this->declaration->finalize($this->writer);
        $this->transport->close($this->writer);

        $this->flushState();
    }

    /**
     * @param string $filename
     * @param array  $namespaces
     *
     * @return Writer
     */
    protected function makeConfiguredWriter(string $filename, array $namespaces): Writer
    {
        $writer = new Writer($filename);
        $writer->openMemory();
        $this->configurator->configure($writer);
        $this->declaration->declare(static::class, $writer, $namespaces);

        return $writer;
    }

    /**
     * @param array $namespaces
     *
     * @return \XMLWriter
     */
    protected function makeWriterHelper(array $namespaces): \XMLWriter
    {
        $writer = new \XMLWriter();
        $writer->openMemory();

        $this->configurator->configure($writer);
        $this->declaration->declare(static::class, $writer, $namespaces);

        $writer->text("\n");
        $writer->flush();

        return $writer;
    }

    /**
     * @param \Spiral\Sitemaps\ElementInterface $entity
     *
     * @return bool
     */
    protected function addElement(ElementInterface $entity): bool
    {
        $size = $this->calcSize($entity);

        if ($this->validator->isEnormousElement($this->state, $size)) {
            throw new EnormousElementException(Utils::bytes($size));
        }

        if (!$this->validator->validate($this->state, $size)) {
            return false;
        }

        $this->write($this->writer, $entity);
        $this->state->addElement($size);
        $this->buffer->add($size);

        $this->flushBufferIfOverflow();

        return true;
    }

    /**
     * @param \Spiral\Sitemaps\ElementInterface $entity
     *
     * @return int
     */
    private function calcSize(ElementInterface $entity): int
    {
        $this->write($this->writerHelper, $entity);
        $data = $this->writerHelper->flush();

        return Utils::length($data);
    }

    /**
     * @param \XMLWriter       $writer
     * @param ElementInterface $element
     *
     * @return mixed
     */
    abstract protected function write(\XMLWriter $writer, ElementInterface $element);

    /**
     *
     */
    private function flushBufferIfOverflow()
    {
        if ($this->bufferOverflow()) {
            $this->transport->append($this->writer);
            $this->buffer->flush();
        }
    }

    /**
     * @return bool
     */
    protected function bufferOverflow(): bool
    {
        return $this->buffer->getElements() === $this->config->bufferElements() || $this->buffer->getSize() >= $this->config->bufferSize();
    }

    /**
     *
     */
    protected function flushState()
    {
        $this->state = null;
        $this->buffer = null;
        $this->writer = null;
        $this->writerHelper = null;
        $this->transport = null;
    }
}