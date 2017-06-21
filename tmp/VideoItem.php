<?php

namespace Spiral\Sitemaps\Items;

use Spiral\Sitemaps\ItemInterface;

/**
 * todo finish
 *
 * @link https://developers.google.com/webmasters/videosearch/sitemaps
 */
class VideoItem implements ItemInterface
{
    /**
     * VideoItem constructor.
     *
     * @param string $thumbnailLoc
     * @param string $title
     * @param string $description
     * @param string $contentLoc
     * @param string $playerLoc
     * @param string $duration
     * @param string $expirationDate
     * @param string $rating
     * @param string $viewCount
     * @param string $publicationDate
     * @param string $familyFriendly
     * @param string $tag
     * @param string $category
     * @param string $restriction
     * @param string $galleryLoc
     * @param string $price
     * @param string $requiresSubscription
     * @param string $uploader
     * @param string $platform
     * @param string $live
     */
    public function __construct(
        string $thumbnailLoc,
        string $title,
        string $description,
        string $contentLoc,
        string $playerLoc,
        string $duration,
        string $expirationDate,
        string $rating,
        string $viewCount,
        string $publicationDate,
        string $familyFriendly,
        string $tag,
        string $category,
        string $restriction,
        string $galleryLoc,
        string $price,
        string $requiresSubscription,
        string $uploader,
        string $platform,
        string $live
    ) {
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $item = $this->loc() . $this->caption() . $this->geoLocation() . $this->title() . $this->license();

        return "<image:image>$item</image:image>";
    }

    /**
     * @return string
     */
    private function loc(): string
    {
        return "<image:loc>{$this->loc}</image:loc>";
    }

    /**
     * @return string
     */
    private function caption(): string
    {
        if (!empty($this->caption)) {
            return "<image:caption>{$this->caption}</image:caption>";
        }

        return '';
    }

    /**
     * @return string
     */
    private function geoLocation(): string
    {
        if (!empty($this->geoLocation)) {
            return "<image:geo_location>{$this->geoLocation}</image:geo_location>";
        }

        return '';
    }

    /**
     * @return string
     */
    private function title(): string
    {
        if (!empty($this->title)) {
            return "<image:title>{$this->title}</image:title>";
        }

        return '';
    }

    /**
     * @return string
     */
    private function license(): string
    {
        if (!empty($this->license)) {
            return "<image:license>{$this->license}</image:license>";
        }

        return '';
    }
}