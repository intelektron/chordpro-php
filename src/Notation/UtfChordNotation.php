<?php

declare(strict_types=1);

namespace ChordPro\Notation;

class UtfChordNotation extends ChordNotation
{
    public const ASCII_TO_UTF = [
        'Abm' => 'A♭m',
        'Bbm' => 'B♭m',
        'Dbm' => 'D♭m',
        'Ebm' => 'E♭m',
        'A#m' => 'A♯m',
        'C#m' => 'C♯m',
        'D#m' => 'D♯m',
        'E#m' => 'E♯m',
        'F#m' => 'F♯m',
        'G#m' => 'G♯m',
        'Ab' => 'A♭',
        'Bb' => 'B♭',
        'Cb' => 'C♭',
        'Db' => 'D♭',
        'Eb' => 'E♭',
        'Fb' => 'F♭',
        'Gb' => 'G♭',
        'A#' => 'A♯',
        'C#' => 'C♯',
        'D#' => 'D♯',
        'F#' => 'F♯',
        'G#' => 'G♯',
    ];

    public const UTF_TO_ASCII = [
        'A♭m' => 'Abm',
        'B♭m' => 'Bbm',
        'D♭m' => 'Dbm',
        'E♭m' => 'Ebm',
        'A♯m' => 'A#m',
        'C♯m' => 'C#m',
        'D♯m' => 'D#m',
        'E♯m' => 'E#m',
        'F♯m' => 'F#m',
        'G♯m' => 'G#m',
        'A♭' => 'Ab',
        'B♭' => 'Bb',
        'C♭' => 'Cb',
        'D♭' => 'Db',
        'E♭' => 'Eb',
        'F♭' => 'Fb',
        'G♭' => 'Gb',
        'A♯' => 'A#',
        'C♯' => 'C#',
        'D♯' => 'D#',
        'F♯' => 'F#',
        'G♯' => 'G#',
    ];

    protected function getToEnglishTable(): array
    {
        return self::UTF_TO_ASCII;
    }

    protected function getFromEnglishTable(): array
    {
        return self::ASCII_TO_UTF;
    }

}
