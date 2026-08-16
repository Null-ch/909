<?php

namespace App\Support;

class PhoneNumber
{
    /**
     * Normalizes a Russian phone number to the canonical +7XXXXXXXXXX form.
     * Accepts leading 7 or 8 as the country code and strips any formatting.
     * Returns the original value unchanged if it can't be normalized, so
     * validation rules can report a proper error message.
     */
    public static function normalize(?string $raw): ?string
    {
        if ($raw === null || trim($raw) === '') {
            return $raw;
        }

        $digits = preg_replace('/\D/', '', $raw);

        if (str_starts_with($digits, '8') && strlen($digits) === 11) {
            $digits = '7'.substr($digits, 1);
        } elseif (! str_starts_with($digits, '7') && strlen($digits) === 10) {
            $digits = '7'.$digits;
        }

        if (strlen($digits) !== 11 || ! str_starts_with($digits, '7')) {
            return $raw;
        }

        return '+'.$digits;
    }
}
