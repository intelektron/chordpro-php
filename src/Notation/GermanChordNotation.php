<?php

declare(strict_types=1);

namespace ChordPro\Notation;

class GermanChordNotation extends ChordNotation
{
    public const ENGLISH_TO_GERMAN = [
        'F#m' => 'fis',
        'C#m' => 'cis',
        'G#m' => 'gis',
        'D#m' => 'dis',
        'A#m' => 'b',
        'E#m' => 'eis',
        'Dbm' => 'des',
        'Abm' => 'as',
        'Ebm' => 'es',
        'Bbm' => 'b',
        'Fb' => 'Fes',
        'Cb' => 'Ces',
        'Gb' => 'Ges',
        'Db' => 'Des',
        'Ab' => 'As',
        'Eb' => 'Es',
        'Bb' => 'B',
        'F#' => 'Fis',
        'C#' => 'Cis',
        'G#' => 'Gis',
        'Fm' => 'f',
        'Cm' => 'c',
        'Gm' => 'g',
        'Dm' => 'd',
        'Am' => 'a',
        'Em' => 'e',
        'Bm' => 'h',
        'B' => 'H',
    ];

    public const GERMAN_TO_ENGLISH = [
        'fis' => 'F#m',
        'cis' => 'C#m',
        'gis' => 'G#m',
        'dis' => 'D#m',
        'eis' => 'E#m',
        'des' => 'Dbm',
        'Fes' => 'Fb',
        'Ces' => 'Cb',
        'Ges' => 'Gb',
        'Des' => 'Db',
        'Fis' => 'F#',
        'Cis' => 'C#',
        'Gis' => 'G#',
        'As' => 'Ab',
        'Es' => 'Eb',
        'as' => 'Abm',
        'es' => 'Ebm',
        'f' => 'Fm',
        'c' => 'Cm',
        'g' => 'Gm',
        'd' => 'Dm',
        'a' => 'Am',
        'e' => 'Em',
        'h' => 'Bm',
        'H' => 'B',
        'b' => 'Bbm',
        'B' => 'Bb',
    ];

    protected function getToEnglishTable(): array
    {
        return self::GERMAN_TO_ENGLISH;
    }

    protected function getFromEnglishTable(): array
    {
        return self::ENGLISH_TO_GERMAN;
    }

}
