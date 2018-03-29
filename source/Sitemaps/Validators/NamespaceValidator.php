<?php

namespace Spiral\Sitemaps\Validators;

use Spiral\Sitemaps\Exceptions;
use Spiral\Sitemaps\Entities;

class NamespaceValidator
{
    /**
     * @param string|null $name
     * @param string      $uri
     *
     * @return bool
     * @throws Exceptions\InvalidNamespaceException
     */
    public function validate(string $name = null, string $uri): bool
    {
        $errors = [];
        $this->validateURI($uri, $errors);
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
     * @param string $uri
     * @param array  $errors
     */
    private function validateURI(string $uri, array &$errors = [])
    {
        if (!preg_match('/^(http(s)?:)?\/\//i', $uri)) {
            $errors['uri'] = "Invalid URI [$uri], HTTP schema is missing.";
        }
    }

    /**
     * @param string|null $name
     * @param array       $errors
     */
    private function validateName(string $name = null, array &$errors = [])
    {
        if (!empty($name)) {
            $basename = preg_replace('/^' . Entities\SitemapNamespace::PREFIX . ':/', '', $name);
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