<?php

namespace Spiral\Sitemaps\Validators;

use Spiral\Sitemaps\Exceptions;
use Spiral\Sitemaps\Entities;

class NamespaceValidator
{
    /**
     * @param string|null $name
     * @param string      $url
     *
     * @return bool
     * @throws Exceptions\InvalidNamespaceException
     */
    public function validate(string $name = null, string $url): bool
    {
        $errors = [];
        $this->validateURL($url, $errors);
        $this->validateName($name, $errors);

        if (empty($errors)) {
            return true;
        }

        throw new Exceptions\InvalidNamespaceException(sprintf(
            'Namespace contains next error(s): "%s".',
            $this->stringifyErrors($errors)
        ));
    }

    /**
     * @param string $url
     * @param array  $errors
     */
    private function validateURL(string $url, array &$errors = [])
    {
        if (!preg_match('/^([\+\-\.a-z0-9]*:)?\/\//i', $url)) {
            $errors['url'] = "Invalid URL [$url], scheme is missing.";
        } elseif (!preg_match('/^([a-z]{1}[\+\-\.a-z0-9]*:)?\/\//i', $url)) {
            $errors['url'] = "Invalid URL [$url], scheme is invalid.";
        }
    }

    /**
     * @param string|null $name
     * @param array       $errors
     */
    private function validateName(string $name = null, array &$errors = [])
    {
        if (!empty($name)) {
            $basename = preg_replace('/^' . Entities\SitemapNamespace::PREFIX . ':/i', '', $name);
            if (mb_strpos($basename, ':') !== false) {
                $errors['name'] = "Invalid name [$name], colon char is not allowed.";
            }
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