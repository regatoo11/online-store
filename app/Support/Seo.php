<?php

namespace App\Support;

class Seo
{
    public static function meta(array $data): string
    {
        $tags = '';

        if (isset($data['title'])) {
            $tags .= '<title>' . e($data['title']) . '</title>' . PHP_EOL;
            $tags .= '<meta name="title" content="' . e($data['title']) . '">' . PHP_EOL;
        }

        if (isset($data['description'])) {
            $tags .= '<meta name="description" content="' . e($data['description']) . '">' . PHP_EOL;
        }

        if (isset($data['keywords'])) {
            $tags .= '<meta name="keywords" content="' . e($data['keywords']) . '">' . PHP_EOL;
        }

        return $tags;
    }

    public static function openGraph(array $data): string
    {
        $tags = '';

        if (isset($data['title'])) {
            $tags .= '<meta property="og:title" content="' . e($data['title']) . '">' . PHP_EOL;
        }

        if (isset($data['description'])) {
            $tags .= '<meta property="og:description" content="' . e($data['description']) . '">' . PHP_EOL;
        }

        if (isset($data['url'])) {
            $tags .= '<meta property="og:url" content="' . e($data['url']) . '">' . PHP_EOL;
        }

        if (isset($data['image'])) {
            $tags .= '<meta property="og:image" content="' . e($data['image']) . '">' . PHP_EOL;
        }

        if (isset($data['type'])) {
            $tags .= '<meta property="og:type" content="' . e($data['type']) . '">' . PHP_EOL;
        }

        return $tags;
    }

    public static function canonical(string $url): string
    {
        return '<link rel="canonical" href="' . e($url) . '">' . PHP_EOL;
    }
}
