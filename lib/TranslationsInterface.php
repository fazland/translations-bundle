<?php

namespace Fazland\TranslationsBundle;

/**
 * @author Alessandro Chitolina <alekitto@gmail.com>
 * @author Giovanni Albero <giovannialbero.solinf@gmail.com>
 * @author Massimiliano Braglia <massimiliano.braglia@fazland.com>
 */
interface TranslationsInterface
{
    /**
     * Return an array containing the Translation Keys for a specific context.
     *
     * @return array
     */
    public function getTranslations();

    /**
     * Return the Translation Domain in which the Translation Keys will be inserted.
     *
     * @return string
     */
    public static function getDomain();

    /**
     * Return the translation locale
     *
     * @return string
     */
    public static function getLocale();
}
