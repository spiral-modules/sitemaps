<?php

namespace Spiral\Sitemaps\Validators;

use Spiral\Sitemaps\Elements\URL;
use Spiral\Sitemaps\Exceptions;

class URLValidator
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

    private $images;

    private $alterLangs;

    public function __construct(ImageValidator $images, AlterLangValidator $alterLangs)
    {
        $this->images = $images;
        $this->alterLangs = $alterLangs;
    }

    /**
     * @param URL $url
     *
     * @return bool
     * @throws Exceptions\InvalidURLException
     */
    public function validate(URL $url): bool
    {
        $errors = [];
        if ($url->hasPriority()) {
            $this->validatePriority($url->getPriority(), $errors);
        }

        if ($url->hasChangeFrequency()) {
            $this->validateChangeFrequency($url->getChangeFrequency(), $errors);
        }

        $this->validateChildrenNodes($url, $errors);

        if (empty($errors)) {
            return true;
        }

        throw new Exceptions\InvalidURLException(sprintf(
            'URL contains next error(s): "%s".',
            $this->stringifyErrors($errors)
        ));
    }

    /**
     * @param URL   $url
     * @param array $errors
     */
    protected function validateChildrenNodes(URL $url, array $errors)
    {
    }

    /**
     * @param float $priority
     * @param array $errors
     */
    private function validatePriority(float $priority, array $errors)
    {
        if ($priority < 0 || $priority > 1) {
            $errors['priority'] = "Invalid sitemap priority [$priority], valid value range is [0.0-1.0].";
        }
    }

    /**
     * @param string $changeFrequency
     * @param array  $errors
     */
    private function validateChangeFrequency(string $changeFrequency, array $errors)
    {
        if (!in_array($changeFrequency, self::FREQUENCIES)) {
            $errors['frequency'] = sprintf(
                "Invalid sitemap frequency [$changeFrequency], valid values are [%s].",
                join(', ', self::FREQUENCIES)
            );
        }
    }

    /**
     * @param array $errors
     *
     * @return string
     */
    private function stringifyErrors(array $errors): string
    {
        return join(' ', $errors);
    }
}