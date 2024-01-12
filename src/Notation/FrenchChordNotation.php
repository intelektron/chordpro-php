<?php

declare(strict_types=1);

namespace ChordPro\Notation;

class FrenchChordNotation extends ChordNotation
{
    public const ENGLISH_TO_FRENCH = [
        'A' => 'La',
        'B' => 'Si',
        'C' => 'Do',
        'D' => 'Ré',
        'E' => 'Mi',
        'F' => 'Fa',
        'G' => 'Sol',
    ];

    public const FRENCH_TO_ENGLISH = [
        'Sol' => 'G',
        'La' => 'A',
        'Si' => 'B',
        'Do' => 'C',
        'Ré' => 'D',
        'Mi' => 'E',
        'Fa' => 'F',
    ];

    protected function getToEnglishTable(): array
    {
        return self::FRENCH_TO_ENGLISH;
    }

    protected function getFromEnglishTable(): array
    {
        return self::ENGLISH_TO_FRENCH;
    }

}
