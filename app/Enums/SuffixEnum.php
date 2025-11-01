<?php

namespace App\Enums;

enum SuffixEnum: string
{
    case JR = 'Jr.';
    case SR = 'Sr.';
    case II = 'II';
    case III = 'III';
    case IV = 'IV';
    case V = 'V';
    case VI = 'VI';
    case VII = 'VII';
    case VIII = 'VIII';
    case IX = 'IX';
    case X = 'X';

    /**
     * Get all suffix values.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
