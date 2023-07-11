<?php

namespace srag\Plugins\Hub2\Object;

use srag\Plugins\Hub2\Exception\LanguageCodeException;

/**
 * Trait LanguageCheck
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
trait LanguageCheck
{
    /**
     * Copied from ilMDLanguageItem::_getPossibleLanguageCodes
     * @var string[]
     */
    private static $available_languages
        = [
            "aa",
            "ab",
            "af",
            "am",
            "ar",
            "as",
            "ay",
            "az",
            "ba",
            "be",
            "bg",
            "bh",
            "bi",
            "bn",
            "bo",
            "br",
            "ca",
            "co",
            "cs",
            "cy",
            "da",
            "de",
            "dz",
            "el",
            "en",
            "eo",
            "es",
            "et",
            "eu",
            "fa",
            "fi",
            "fj",
            "fo",
            "fr",
            "fy",
            "ga",
            "gd",
            "gl",
            "gn",
            "gu",
            "ha",
            "he",
            "hi",
            "hr",
            "hu",
            "hy",
            "ia",
            "ie",
            "ik",
            "id",
            "is",
            "it",
            "iu",
            "ja",
            "jv",
            "ka",
            "kk",
            "kl",
            "km",
            "kn",
            "ko",
            "ks",
            "ku",
            "ky",
            "la",
            "ln",
            "lo",
            "lt",
            "lv",
            "mg",
            "mi",
            "mk",
            "ml",
            "mn",
            "mo",
            "mr",
            "ms",
            "mt",
            "my",
            "na",
            "ne",
            "nl",
            "no",
            "oc",
            "om",
            "or",
            "pa",
            "pl",
            "ps",
            "pt",
            "qu",
            "rm",
            "rn",
            "ro",
            "ru",
            "rw",
            "sa",
            "sd",
            "sg",
            "sh",
            "si",
            "sk",
            "sl",
            "sm",
            "sn",
            "so",
            "sq",
            "sr",
            "ss",
            "st",
            "su",
            "sv",
            "sw",
            "ta",
            "te",
            "tg",
            "th",
            "ti",
            "tk",
            "tl",
            "tn",
            "to",
            "tr",
            "ts",
            "tt",
            "tw",
            "ug",
            "uk",
            "ur",
            "uz",
            "vi",
            "vo",
            "wo",
            "xh",
            "yi",
            "yo",
            "za",
            "zh",
            "zu",
        ];

    public static function isLanguageCode(string $languageCode) : bool
    {
        return in_array($languageCode, self::$available_languages);
    }

    /**
     * @throws LanguageCodeException
     */
    public static function checkLanguageCode(string $languageCode)
    {
        if (!self::isLanguageCode($languageCode)) {
            throw new LanguageCodeException($languageCode);
        }
    }
}
