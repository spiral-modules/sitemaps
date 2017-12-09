<?php

namespace Spiral\Sitemaps\Items;

use Spiral\Sitemaps\Interfaces\SitemapItemInterface;

/**
 * todo finish
 *
 */
class Video implements SitemapItemInterface
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
        $data = '';

        return "<video:video>$data</video:video>";
    }
}