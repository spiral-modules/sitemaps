<?php

namespace Spiral\Sitemaps\Validators;

use Spiral\Sitemaps\SitemapNamespace;

class NamespaceValidator
{
    public function validate(string $name = null, string $uri)
    {
        $errors = [];
        if (!preg_match('/^http(s)?:\/\//i', $uri)) {
            $errors['uri'] = 'Invalid URL, HTTP schema is missing';
        }

        if (!empty($name)) {
            preg_match('/^(' . SitemapNamespace::PREFIX . ':)?[^\:]+');
        }
    }
}