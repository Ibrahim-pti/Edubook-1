<?php

namespace App\Support;

/**
 * Iraqi mobile numbers, restricted to the three carriers we accept:
 * Korek (075x), Asiacell (077x) and Zain (078x).
 *
 * Numbers are stored in E.164 form (e.g. +9647501234567) so the admin panel
 * can link them with tel: and so every account uses one consistent format.
 */
class IraqPhone
{
    public const COUNTRY_CODE = '+964';

    /** 10-digit national number: 7 + carrier prefix + 7 digits. */
    private const NATIONAL_PATTERN = '/^7(5[0-4]|7[0-4]|8[0-4])[0-9]{7}$/';

    /**
     * Strips formatting plus any country/trunk prefix, leaving the bare
     * national number, or null when nothing usable is left.
     */
    public static function national(?string $raw): ?string
    {
        if ($raw === null) {
            return null;
        }

        $digits = preg_replace('/\D/', '', $raw) ?? '';

        if (str_starts_with($digits, '00964')) {
            $digits = substr($digits, 5);
        } elseif (str_starts_with($digits, '964')) {
            $digits = substr($digits, 3);
        }

        if (str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }

        return $digits === '' ? null : $digits;
    }

    public static function isValid(?string $raw): bool
    {
        $national = self::national($raw);

        return $national !== null && preg_match(self::NATIONAL_PATTERN, $national) === 1;
    }

    /** E.164 form for storage, or null when the number is not accepted. */
    public static function normalize(?string $raw): ?string
    {
        return self::isValid($raw) ? self::COUNTRY_CODE . self::national($raw) : null;
    }

    /** Validation rule for any validate() call; empty values pass through. */
    public static function rule(): \Closure
    {
        return function (string $attribute, $value, \Closure $fail) {
            if (filled($value) && !self::isValid($value)) {
                $fail('تەنها ژمارەی کورەک، ئاسیاسێل و زەین (+964) وەردەگیرێت.');
            }
        };
    }
}
