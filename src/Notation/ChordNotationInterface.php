<?php

declare(strict_types=1);

namespace ChordPro\Notation;

use ChordPro\Chord;

/**
 * The common methods for chord converters
 */
interface ChordNotationInterface
{
    /**
     * Convert a chord ext to this notation.
     *
     * @param string $chordExt
     * @return string
     */
    public function convertExtToNotation(string $chordExt): string;

    /**
     * Convert a key (chord root) to this notation.
     *
     * @param string $chordRoot
     * @return string
     */
    public function convertChordRootToNotation(string $chordRoot): string;

    /**
     * Convert a key (chord root) from this notation.
     *
     * @param string $chordRoot
     * @return string
     */
    public function convertChordRootFromNotation(string $chordRoot): string;

}
