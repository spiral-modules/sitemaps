<?php

namespace Spiral\Sitemaps\Items;

/**
 * @link https://support.google.com/webmasters/topic/6080646
 */
class ExtendedPageItem extends PageItem
{
    /** @var ImageItems */
    private $images;

    /** @var VideoItems */
    private $videos;

    /** @var AlterLangItems */
    private $alterLangs;

    /**
     * Add images to item.
     *
     * @param ImageItems $images
     * @return ExtendedPageItem
     */
    public function withImages(ImageItems $images): self
    {
        $clone = clone $this;
        $clone->images = $images;

        return $clone;
    }

    ///**
    // * Add video to sitemap.
    // *
    // * @param VideoItems $videos
    // * @return ExtendedPageItem
    // */
    //public function withVideos(VideoItems $videos): self
    //{
    //    $clone = clone $this;
    //    $clone->videos = $videos;
    //
    //    return $clone;
    //}

    /**
     * Add alter langs to item.
     *
     * @param AlterLangItems $alterLangs
     * @return ExtendedPageItem
     */
    public function withAlterLangs(AlterLangItems $alterLangs): self
    {
        $clone = clone $this;
        $clone->alterLangs = $alterLangs;

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function render(): string
    {
        $data = $this->loc() . $this->lastmod() . $this->changefreq() . $this->priority()
            . $this->images() . $this->videos() . $this->alterLangs();

        return sprintf('<url>%s</url>', $data);
    }

    /**
     * Render images.
     *
     * @return string
     */
    protected function images(): string
    {
        if (!empty($this->images)) {
            return $this->images->render();
        }

        return '';
    }

    /**
     * Render video.
     *
     * @return string
     */
    protected function videos(): string
    {
        if (!empty($this->videos)) {
            return $this->videos->render();
        }

        return '';
    }

    /**
     * Render alter langs.
     *
     * @return string
     */
    protected function alterLangs(): string
    {
        if (!empty($this->alterLangs)) {
            return $this->alterLangs->render();
        }

        return '';
    }
}