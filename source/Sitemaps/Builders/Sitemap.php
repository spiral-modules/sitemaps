<?php

namespace Spiral\Sitemaps\Builders;

use Spiral\Sitemaps\Configs\BuilderConfig;
use Spiral\Sitemaps\Configurator;
use Spiral\Sitemaps\Declaration;
use Spiral\Sitemaps\Entities;
use Spiral\Sitemaps\Exceptions\EnormousElementException;
use Spiral\Sitemaps\Exceptions\WorkflowException;
use Spiral\Sitemaps\Reservation;
use Spiral\Sitemaps\TransportInterface;
use Spiral\Sitemaps\Utils;
use Spiral\Sitemaps\Validators\SitemapValidator;
use Spiral\Sitemaps\Writer;
use Spiral\Sitemaps\Patterns\URLPattern;

class Sitemap
{
    /** @var URLPattern */
    private $pattern;

    /** @var SitemapValidator */
    private $validator;

    /** @var Declaration */
    private $declaration;

    /** @var Reservation */
    private $reservation;

    /** @var Configurator */
    private $configurator;

    /** @var Writer\State */
    private $state;

    /** @var Writer\Buffer */
    private $buffer;

    /** @var Writer|null */
    private $writer;

    /** @var Writer|null */
    private $writerHelper;

    /** @var BuilderConfig */
    private $config;

    /** @var TransportInterface|null */
    private $transport;

    public function __construct(
        URLPattern $pattern,
        SitemapValidator $validator,
        Declaration $declaration,
        Reservation $reservation,
        Configurator $configurator,
        BuilderConfig $config
    ) {
        $this->pattern = $pattern;
        $this->validator = $validator;
        $this->declaration = $declaration;
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

    /**
     * @param \Spiral\Sitemaps\Entities\URL $url
     *
     * @return bool
     */
    public function addURL(Entities\URL $url)
    {
        $this->writeElement($this->writerHelper, $url);
        $data = $this->writerHelper->flush();
        $size = Utils::length($data);

        if ($this->validator->isEnormousElement($this->state, $size)) {
            throw new EnormousElementException(Utils::bytes($size));
        }

        if ($this->validator->validate($this->state, $size)) {
            $this->writeElement($this->writer, $url);
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

    public function end()
    {
        if (empty($this->writer)) {
            throw new WorkflowException('XML writer is already closed.');
        }

        $this->declaration->finalize($this->writer);
        $this->transport->close($this->writer);

        $this->writer = null;
        $this->writerHelper = null;
        $this->transport = null;
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
    private function bufferOverflow(): bool
    {
        return $this->buffer->getElements() === $this->config->bufferElements() || $this->buffer->getSize() >= $this->config->bufferSize();
    }

    private function writeElement(\XMLWriter $writer, Entities\URL $url)
    {
        $this->pattern->write($writer, $url);
    }
}