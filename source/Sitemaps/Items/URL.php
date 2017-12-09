<?php

namespace Spiral\Sitemaps\Items;

use Spiral\Sitemaps\Exceptions\InvalidPriorityException;
use Spiral\Sitemaps\Exceptions\InvalidFrequencyException;
use Spiral\Sitemaps\Interfaces\SitemapItemInterface;

class URL implements SitemapItemInterface
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

    /** @var Image[] */
    private $images = [];

    /** @var Video[] */
    private $videos = [];

    /** @var AlterLang[] */
    private $alterLangs = [];

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
            throw new InvalidFrequencyException($changefreq);
        }

        if (!empty($priority) && ($priority < 0 || $priority > 1)) {
            throw new InvalidPriorityException($priority);
        }

        $this->loc = $loc;
        $this->lastmod = $lastmod;
        $this->changefreq = $changefreq;
        $this->priority = $priority;
    }

    /**
     * Add image item.
     *
     * @param Image $image
     * @return $this
     */
    public function addImage(Image $image)
    {
        $this->images[] = $image;

        return $this;
    }

    /**
     * Add alter lang item.
     *
     * @param AlterLang $lang
     * @return $this
     */
    public function addAlterLang(AlterLang $lang)
    {
        $this->alterLangs[] = $lang;

        return $this;
    }

    /**
     * Add video item.
     *
     * @param Video $video
     * @return $this
     */
    public function addVideo(Video $video)
    {
        $this->videos[] = $video;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function render(): string
    {
        $loc = $this->renderLoc();
        $lastmod = $this->renderLastmod();
        $changefreq = $this->renderChangefreq();
        $priority = $this->renderPriority();
        $images = $this->renderImages();
        $alterLangs = $this->renderAlterLangs();

        return "<url>{$loc}{$lastmod}{$changefreq}{$priority}{$images}{$alterLangs}</url>";
    }

    /**
     * Render location.
     *
     * @return string
     */
    protected function renderLoc(): string
    {
        return "<loc>{$this->loc}</loc>";
    }

    /**
     * Render last modification time.
     *
     * @return string|null
     */
    protected function renderLastmod(): string
    {
        if (!empty($this->lastmod)) {
            return "<lastmod>{$this->lastmod->format('c')}</lastmod>";
        }

        return null;
    }

    /**
     * Render change frequency.
     *
     * @return string|null
     */
    protected function renderChangefreq(): string
    {
        if (!empty($this->changefreq)) {
            return "<lastmod>{$this->changefreq}</changefreq>";
        }

        return '';
    }

    /**
     * Render priority.
     *
     * @return string
     */
    protected function renderPriority(): string
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
    protected function renderImages(): string
    {
        $images = [];
        foreach ($this->images as $image) {
            $images[] = $image->render();
        }

        return join('', $images);
    }

    /**
     * Render alter langs.
     *
     * @return string
     */
    protected function renderAlterLangs(): string
    {
        $langs = [];
        foreach ($this->alterLangs as $lang) {
            $langs[] = $lang->render();
        }

        return join('', $langs);
    }

    /**
     * Render videos.
     *
     * @return string
     */
    protected function renderVideos(): string
    {
        $videos = [];
        foreach ($this->videos as $video) {
            $videos[] = $video->render();
        }

        return join('', $videos);
    }
}