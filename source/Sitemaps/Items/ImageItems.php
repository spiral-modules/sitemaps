<?php

namespace Spiral\Sitemaps\Items;

use Spiral\Sitemaps\ItemInterface;

class ImageItems implements ItemInterface
{
    /** @var ImageItem[] */
    private $images = [];

    /**
     * ImageItem constructor.
     *
     * @param array $images
     * @param int   $maxFiles
     */
    public function __construct(array $images, int $maxFiles = 1000)
    {
        if ($maxFiles < 0 || $maxFiles > 1000) {
            throw new \OutOfRangeException(sprintf(
                'Unsupported images limit "%s". Valid values range is 0-1000.',
                $maxFiles
            ));
        }

        foreach (array_slice($images, 0, $maxFiles) as $image) {
            $this->images[] = $this->make($image);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function render(): string
    {
        $output = [];
        foreach ($this->images as $image) {
            $output[] = $image->render();
        }

        return join('', $output);
    }

    /**
     * Make Item based on given input.
     *
     * @param array $input
     * @return ImageItem
     */
    protected function make(array $input): ImageItem
    {
        $loc = $input['loc'] ?? null;
        if (empty($loc)) {
            throw new \InvalidArgumentException('Image location value is missing ("loc" key).');
        }

        return new ImageItem(
            $loc,
            $input['caption'] ?? null,
            $input['geo_location'] ?? null,
            $input['title'] ?? null,
            $input['license'] ?? null
        );
    }
}