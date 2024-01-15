<?php

declare(strict_types=1);

namespace ChordPro\Notation;

class FrenchChordNotation extends ChordNotation
{
    public const ENGLISH_TO_FRENCH = [
        'Abm' => 'La♭m',
        'Bbm' => 'Si♭m',
        'Dbm' => 'Ré♭m',
        'Ebm' => 'Mi♭m',
        'A#m' => 'La♯m',
        'C#m' => 'Do♯m',
        'D#m' => 'Ré♯m',
        'E#m' => 'Mi♯m',
        'F#m' => 'Fa♯m',
        'G#m' => 'Sol♯m',
        'Ab' => 'La♭',
        'Bb' => 'Si♭',
        'Cb' => 'Do♭',
        'Db' => 'Ré♭',
        'Eb' => 'Mi♭',
        'Fb' => 'Fa♭',
        'Gb' => 'Sol♭',
        'A#' => 'La♯',
        'C#' => 'Do♯',
        'D#' => 'Ré♯',
        'F#' => 'Fa♯',
        'G#' => 'Sol♯',
        'Am' => 'Lam',
        'Bm' => 'Sim',
        'Cm' => 'Dom',
        'Dm' => 'Rém',
        'Em' => 'Mim',
        'Fm' => 'Fam',
        'Gm' => 'Solm',
        'A' => 'La',
        'B' => 'Si',
        'C' => 'Do',
        'D' => 'Ré',
        'E' => 'Mi',
        'F' => 'Fa',
        'G' => 'Sol',
    ];

    public const FRENCH_TO_ENGLISH = [
        'Sol♯m' => 'G#m',
        'Sol#m' => 'G#m',
        'La♭m' => 'Abm',
        'Si♭m' => 'Bbm',
        'Ré♭m' => 'Dbm',
        'Mi♭m' => 'Ebm',
        'Labm' => 'Abm',
        'Sibm' => 'Bbm',
        'Rébm' => 'Dbm',
        'Mibm' => 'Ebm',
        'La♯m' => 'A#m',
        'Do♯m' => 'C#m',
        'Ré♯m' => 'D#m',
        'Mi♯m' => 'E#m',
        'Fa♯m' => 'F#m',
        'La#m' => 'A#m',
        'Do#m' => 'C#m',
        'Ré#m' => 'D#m',
        'Mi#m' => 'E#m',
        'Fa#m' => 'F#m',
        'Sol♭' => 'Gb',
        'Solb' => 'Gb',
        'Sol♯' => 'G#',
        'Sol#' => 'G#',
        'Solm' => 'Gm',
        'La♭' => 'Ab',
        'Si♭' => 'Bb',
        'Do♭' => 'Cb',
        'Ré♭' => 'Db',
        'Mi♭' => 'Eb',
        'Fa♭' => 'Fb',
        'Lab' => 'Ab',
        'Sib' => 'Bb',
        'Dob' => 'Cb',
        'Réb' => 'Db',
        'Mib' => 'Eb',
        'Fab' => 'Fb',
        'La♯' => 'A#',
        'Do♯' => 'C#',
        'Ré♯' => 'D#',
        'Fa♯' => 'F#',
        'La#' => 'A#',
        'Do#' => 'C#',
        'Ré#' => 'D#',
        'Fa#' => 'F#',
        'Lam' => 'Am',
        'Sim' => 'Bm',
        'Dom' => 'Cm',
        'Rém' => 'Dm',
        'Mim' => 'Em',
        'Fam' => 'Fm',
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
