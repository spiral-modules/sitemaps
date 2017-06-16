<?php

namespace Spiral\Sitemaps\Items;

use Spiral\Sitemaps\ItemInterface;

class AlterLangItems implements ItemInterface
{
    /** @var AlterLangItem[] */
    private $alterLangs = [];

    /**
     * ImageItem constructor.
     *
     * @param array $alterLangs
     */
    public function __construct(array $alterLangs)
    {
        foreach ($alterLangs as $alterLang) {
            $this->alterLangs[] = $this->make($alterLang);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function render(): string
    {
        $output = [];
        foreach ($this->alterLangs as $alterLang) {
            $output[] = $alterLang->render();
        }

        return join('', $output);
    }

    /**
     * Make Item based on given input.
     *
     * @param array $input
     * @return AlterLangItem
     */
    protected function make(array $input): AlterLangItem
    {
        $hreflang = $input['hreflang'] ?? null;
        $href = $input['href'] ?? null;

        $missing = [];
        if (empty($hreflang)) {
            $missing[] = $hreflang;
        }

        if (empty($href)) {
            $missing[] = $href;
        }

        if (!empty($missing)) {
            throw new \InvalidArgumentException(sprintf(
                'Required data is missing ("%s" keys).',
                join(', ', $missing)
            ));
        }

        return new AlterLangItem($hreflang, $href);
    }
}