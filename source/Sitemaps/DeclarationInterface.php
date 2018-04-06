<?php

namespace Spiral\Sitemaps;

interface DeclarationInterface
{
    /**
     * @param \XMLWriter $writer
     * @param array      $namespaces
     */
    public function declare(\XMLWriter $writer, array $namespaces = []);

    /**
     * @param \XMLWriter $writer
     */
    public function finalize(\XMLWriter $writer);
}