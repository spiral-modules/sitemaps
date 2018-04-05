<?php

namespace Spiral\Sitemaps;

class Utils
{
    /**
     * Format bytes to human-readable format.
     *
     * @param int $bytes    Size in bytes.
     * @param int $decimals The number of decimals include to output. Set to 1 by default.
     *
     * @return string
     */
    public static function bytes(int $bytes, int $decimals = 1): string
    {
        $pows = ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        for ($unit = 0; $bytes > 1024; $unit++) {
            $bytes /= 1024;
        }

        return number_format($bytes, $unit ? $decimals : 0) . " " . $pows[$unit];
    }

    /**
     * @param $string
     *
     * @return int
     */
    public static function length($string): int
    {
        return mb_strlen($string, '8bit');
    }

    /**
     * @param resource $resource
     */
    public static function assertResource($resource)
    {
        if (false === is_resource($resource)) {
            $type = gettype($resource);
            throw new \InvalidArgumentException("Argument must be a valid resource type. $type given.");
        }
    }
}