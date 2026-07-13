<?php

namespace App\Support;

/**
 * Central definition of the locales supported by the store.
 */
final class Locale
{
    /**
     * Supported locales. Arabic is the default (see APP_LOCALE).
     *
     * @var list<string>
     */
    public const SUPPORTED = ['ar', 'en'];

    public static function isSupported(string $locale): bool
    {
        return in_array($locale, self::SUPPORTED, true);
    }
}
