<?php

namespace Spiral\Sitemaps\Items;

use Spiral\Sitemaps\ItemInterface;

/**
 * @link https://support.google.com/webmasters/topic/6080646
 */
class PageItem implements ItemInterface
{
    /**
     * Allowed frequencies.
     */
    const FREQUENCIES = [
        'always',
        'hourly',
        'daily',
        'weekly',
        'monthly',
        'yearly',
        'never',
    ];

    /** @var ImageItem[] */
    private $images = [];

    /** @var AlterLangItem[] */
    private $alterLangs;

    /** @var string */
    private $loc;

    /** @var \DateTimeInterface|null */
    private $lastmod;

    /** @var null|string */
    private $changefreq;

    /** @var float|null */
    private $priority;

    /**
     * PageItem constructor.
     *
     * @param string                  $loc
     * @param \DateTimeInterface|null $lastmod
     * @param string|null             $changefreq
     * @param float|null              $priority
     */
    public function __construct(
        string $loc,
        \DateTimeInterface $lastmod = null,
        string $changefreq = null,
        float $priority = null
    ) {
        if (!empty($changefreq) && !in_array($changefreq, self::FREQUENCIES)) {
            throw new \OutOfRangeException(sprintf(
                'Unsupported sitemap frequency "%s".',
                $changefreq
            ));
        }

        if (!empty($priority) && ($priority < 0 || $priority > 1)) {
            throw new \OutOfRangeException(sprintf(
                'Unsupported sitemap priority "%s". Valid values range is 0.0-1.0.',
                $priority
            ));
        }

        $this->loc = $loc;
        $this->lastmod = $lastmod;
        $this->changefreq = $changefreq;
        $this->priority = $priority;
    }

    /**
     * Add image item.
     *
     * @param ImageItem $image
     */
    public function addImage(ImageItem $image)
    {
        $this->images[] = $image;
    }

    /**
     * Add alter lang item.
     *
     * @param AlterLangItem $lang
     */
    public function addAlterLang(AlterLangItem $lang)
    {
        $this->alterLangs[] = $lang;
    }

    /**
     * {@inheritdoc}
     */
    public function render(): string
    {
        $data = $this->loc() . $this->lastmod() . $this->changefreq() . $this->priority() . $this->images() . $this->alterLangs();

        return sprintf('<url>%s</url>', $data);
    }

    /**
     * Render location.
     *
     * @return string
     */
    protected function loc(): string
    {
        return sprintf('<loc>%s</loc>', $this->loc);
    }

    /**
     * Render last modification time.
     *
     * @return string
     */
    protected function lastmod(): string
    {
        if (!empty($this->lastmod)) {
            return sprintf('<lastmod>%s</lastmod>', $this->lastmod->format('c'));
        }

        return '';
    }

    /**
     * Render change frequency.
     *
     * @return string
     */
    protected function changefreq(): string
    {
        if (!empty($this->changefreq)) {
            return sprintf('<changefreq>%s</changefreq>', $this->changefreq);
        }

        return '';
    }

    /**
     * Render priority.
     *
     * @return string
     */
    protected function priority(): string
    {
        if (!empty($this->priority)) {
            return sprintf('<priority>%s</priority>', $this->priority);
        }

        return '';
    }

    /**
     * Render images.
     *
     * @return string
     */
    protected function images(): string
    {
        $data = '';
        foreach ($this->images as $image) {
            $data .= $image->render();
        }

        return $data;
    }

    /**
     * Render alter langs.
     *
     * @return string
     */
    protected function alterLangs(): string
    {
        $data = '';
        foreach ($this->alterLangs as $lang) {
            $data .= $lang->render();
        }

        return $data;
    }
}