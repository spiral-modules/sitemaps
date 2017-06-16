<?php

namespace Spiral\Sitemaps\Items;

use Spiral\Sitemaps\ItemInterface;

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
     * {@inheritdoc}
     */
    public function render(): string
    {
        $data = $this->loc() . $this->lastmod() . $this->changefreq() . $this->priority();

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
}