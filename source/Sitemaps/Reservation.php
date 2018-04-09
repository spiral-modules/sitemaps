<?php

namespace Spiral\Sitemaps;

class Reservation
{
    /** @var Declaration */
    private $declaration;

    /** @var Configurator */
    private $configurator;

    /**
     * @param Declaration  $declaration
     * @param Configurator $configurator
     */
    public function __construct(Declaration $declaration, Configurator $configurator)
    {
        $this->declaration = $declaration;
        $this->configurator = $configurator;
    }

    /**
     * @param string $builder
     * @param array  $namespaces
     *
     * @return int
     */
    public function calculateSize(string $builder, array $namespaces = []): int
    {
        $writer = $this->getConfiguredWriter();
        $this->declareWriter($builder, $writer, $namespaces);

        return $this->calculate($writer);
    }

    /**
     * @return \XMLWriter
     */
    private function getConfiguredWriter(): \XMLWriter
    {
        $writer = new \XMLWriter();
        $writer->openMemory();
        $this->configurator->configure($writer);

        return $writer;
    }

    /**
     * @param string     $builder
     * @param \XMLWriter $writer
     * @param array      $namespaces
     */
    private function declareWriter(string $builder, \XMLWriter $writer, array $namespaces)
    {
        $this->declaration->declare($builder, $writer, $namespaces);
        $writer->text("\n");
        $this->declaration->finalize($writer);
    }

    /**
     * @param \XMLWriter $writer
     *
     * @return int
     */
    private function calculate(\XMLWriter $writer): int
    {
        return Utils::length($writer->flush());
    }
}
