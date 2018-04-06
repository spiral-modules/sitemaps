<?php

namespace Spiral\Sitemaps\Builders;

use Spiral\Sitemaps\Configs\BuilderConfig;
use Spiral\Sitemaps\Configurator;
use Spiral\Sitemaps\DeclarationInterface;
use Spiral\Sitemaps\EntityInterface;
use Spiral\Sitemaps\Exceptions\EnormousElementException;
use Spiral\Sitemaps\Exceptions\WorkflowException;
use Spiral\Sitemaps\PatternInterface;
use Spiral\Sitemaps\Reservation;
use Spiral\Sitemaps\TransportInterface;
use Spiral\Sitemaps\Utils;
use Spiral\Sitemaps\Validators\SitemapValidator;
use Spiral\Sitemaps\Writer;

class AbstractBuilder
{
    /** @var \Spiral\Sitemaps\PatternInterface */
    protected $pattern;

    /** @var SitemapValidator */
    protected $validator;

    /** @var Writer\State */
    protected $state;

    /** @var Writer\Buffer */
    protected $buffer;

    /** @var Writer|null */
    protected $writer;

    /** @var Writer|null */
    protected $writerHelper;

    /** @var TransportInterface|null */
    protected $transport;

    /** @var \Spiral\Sitemaps\DeclarationInterface */
    protected $declaration;

    /** @var Reservation */
    private $reservation;

    /** @var Configurator */
    private $configurator;

    /** @var BuilderConfig */
    private $config;

    public function __construct(
        PatternInterface $pattern,
        DeclarationInterface $declaration,
        SitemapValidator $validator,
        Reservation $reservation,
        Configurator $configurator,
        BuilderConfig $config
    ) {
        $this->pattern = $pattern;
        $this->declaration = $declaration;
        $this->validator = $validator;
        $this->reservation = $reservation;
        $this->configurator = $configurator;
        $this->config = $config;
        $this->state = new Writer\State();
        $this->buffer = new Writer\Buffer();
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

        $this->transport = $transport;

        $this->state->reserveFilesize($this->reservation->calculateSize($namespaces));
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
    private function makeConfiguredWriter(string $filename, array $namespaces): Writer
    {
        $writer = new Writer($filename);
        $writer->openMemory();
        $this->configurator->configure($writer);
        $this->declaration->declare($writer, $namespaces);

        return $writer;
    }

    /**
     * @param array $namespaces
     *
     * @return \XMLWriter
     */
    private function makeWriterHelper(array $namespaces): \XMLWriter
    {
        $writer = new \XMLWriter();
        $writer->openMemory();

        $this->configurator->configure($writer);
        $this->declaration->declare($writer, $namespaces);

        $writer->text("\n");
        $writer->flush();

        return $writer;
    }

    /**
     * @return bool
     */
    protected function bufferOverflow(): bool
    {
        return $this->buffer->getElements() === $this->config->bufferElements() || $this->buffer->getSize() >= $this->config->bufferSize();
    }

    /**
     * @link https://www.sitemaps.org/protocol.html#index
     *
     * @param \Spiral\Sitemaps\EntityInterface $entity
     *
     * @return bool
     */
    protected function addElement(EntityInterface $entity): bool
    {
        $size = $this->calcSize($entity);

        if ($this->validator->isEnormousElement($this->state, $size)) {
            throw new EnormousElementException(Utils::bytes($size));
        }

        if ($this->validator->validate($this->state, $size)) {
            $this->pattern->write($this->writer, $entity);
            $this->state->addElement($size);
            $this->buffer->add($size);

            if ($this->bufferOverflow()) {
                $this->transport->append($this->writer);
                $this->buffer->flush();
            }

            return true;
        }

        return false;
    }

    /**
     * @param \Spiral\Sitemaps\EntityInterface $entity
     *
     * @return int
     */
    private function calcSize(EntityInterface $entity): int
    {
        $this->pattern->write($this->writerHelper, $entity);
        $data = $this->writerHelper->flush();

        return Utils::length($data);
    }

    protected function flushState()
    {
        $this->writer = null;
        $this->writerHelper = null;
        $this->transport = null;
    }
}