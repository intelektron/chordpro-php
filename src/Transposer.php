<?php

declare(strict_types=1);

/*
* Two use-cases :
*
* - knowing original key of song, it transposes with table to ensure musicaly matching chords
* - without original key, simple mathematical transpose (with errors !)
*
*
*/

namespace ChordPro;

use ChordPro\Line\Lyrics;

class Transposer
{
    /**
     * @var int[] The table of chord key mappings to semitones.
     */
    private array $simpleTransposeTable = [
        'C'   => 0,
        'C#'  => 1,
        'Db'  => 1,
        'D'   => 2,
        'D#'  => 3,
        'Eb'  => 3,
        'E'   => 4,
        'F'   => 5,
        'F#'  => 6,
        'Gb'  => 6,
        'G'   => 7,
        'G#'  => 8,
        'Ab'  => 8,
        'A'   => 9,
        'A#'  => 10,
        'Bb'  => 10,
        'B'   => 11,
    ];

    /**
     * @var int[] The table of chord key mappings for $transposetable.
     */
    private array $transposeChords = [
        'Fb' => 0,
        'Cb' => 1,
        'Gb' => 2,
        'Db' => 3,
        'Ab' => 4,
        'Eb' => 5,
        'Bb' => 6,
        'F' => 7,
        'C' => 8,
        'G' => 9,
        'D' => 10,
        'A' => 11,
        'E' => 12,
        'B' => 13,
        'F#' => 14,
        'C#' => 15,
        'G#' => 16,
        'Dbm' => 0,
        'Abm' => 1,
        'Ebm' => 2,
        'Bbm' => 3,
        'Fm' => 4,
        'Cm' => 5,
        'Gm' => 6,
        'Dm' => 7,
        'Am' => 8,
        'Em' => 9,
        'Bm' => 10,
        'F#m' => 11,
        'C#m' => 12,
        'G#m' => 13,
        'D#m' => 14,
        'A#m' => 15,
        'E#m' => 16
    ];

    /**
     * K for natural, X for ##, bb for bb :)
     *
     * @var string[][] The table of chord key mappings to transpose.
     */
    private array $transposeTable = [
        ['Fb','F','Gbb','Gb','G','Abb','Ab','A','Bbb','Bb','Cbb','Cb','C','Dbb','Db','D','Ebb','Eb'],
        ['Cb','C','Dbb','Db','D','Ebb','Eb','E','Fb','F','Gbb','Gb','G','Abb','Ab','A','Bbb','Bb'],
        ['Gb','G','Abb','Ab','A','Bbb','Bb','B','Cb','C','Dbb','Db','D','Ebb','Eb','E','Fb','F'],
        ['Db','D','Ebb','Eb','E','Fb','F','F#','Gb','G','Abb','Ab','A','Bbb','Bb','B','Cb','C'],
        ['Ab','A','Bbb','Bb','B','Cb','C','C#','Db','D','Ebb','Eb','E','Fb','F','F#','Gb','G'],
        ['Eb','E','Fb','F','F#','Gb','G','G#','Ab','A','Bbb','Bb','B','Cb','C','C#','Db','D'],
        ['Bb','B','Cb','C','C#','Db','D','D#','Eb','E','Fb','F','F#','Gb','G','G#','Ab','A'],
        ['F','F#','Gb','G','G#','Ab','A','A#','Bb','B','Cb','C','C#','Db','D','D#','Eb','E'],
        ['C','C#','Db','D','D#','Eb','E','E#','F','F#','Gb','G','G#','Ab','A','A#','Bb','B'],
        ['G','G#','Ab','A','A#','Bb','B','B#','C','C#','Db','D','D#','Eb','E','E#','F','F#'],
        ['D','D#','Eb','E','E#','F','F#','FX','G','G#','Ab','A','A#','Bb','B','B#','C','C#'],
        ['A','A#','Bb','B','B#','C','C#','CX','D','D#','Eb','E','E#','F','F#','FX','G','G#'],
        ['E','E#','F','F#','FX','G','G#','GX','A','A#','Bb','B','B#','C','C#','CX','D','D#'],
        ['B','B#','C','C#','CX','D','D#','DX','E','E#','F','F#','FX','G','G#','GX','A','A#'],
        ['F#','FX','G','G#','GX','A','A#','AX','B','B#','C','C#','CX','D','D#','DX','E','E#'],
        ['C#','CX','D','D#','DX','E','E#','EX','F#','FX','G','G#','GX','A','A#','AX','B','B#'],
        ['G#','GX','A','A#','AX','B','B#','BX','C#','CX','D','D#','DX','E','E#','EX','F#','FX']
    ];

    /**
     * Transpose a song.
     *
     * @param Song $song The song object.
     * @param int|string $value The target transposition. It can be eiteher a number of semitones, or a key.
     * @return void
     */
    public function transpose(Song $song, int|string $value)
    {
        foreach ($song->getLines() as $line) {
            if ($line instanceof Lyrics) {
                foreach ($line->getBlocks() as $block) {
                    $chords = $block->getChords();
                    if (count($chords) > 0) {
                        if (is_numeric($value)) {
                            $this->simpleTranspose($chords, intval($value));
                        } elseif(!is_null($song->getKey())) {
                            $this->completeTranspose($chords, $song->getKey(), $value);
                            $song->setKey($value);
                        }
                    }
                }
            }
        }
    }

    /**
     * Transpose a song by semitones.
     *
     * @param Chord[] $chords The chords to transpose.
     * @param int $value The number of semitones to transpose.
     */
    private function simpleTranspose(array $chords, int $value): void
    {
        foreach ($chords as $chord) {
            if (!$chord->isKnown()) {
                continue;
            }

            if ($value !== 0 && $value < 12 && $value > -12) {
                $suffix = $chord->isMinor() ? 'm' : '';
                $key = $this->simpleTransposeTable[trim($chord->getRootChord(), 'm')];
                $new_key = ($key + $value < 0) ? 12 + ($key + $value) : ($key + $value) % 12;
                $chord->transposeTo(array_search($new_key, $this->simpleTransposeTable, true) . $suffix);
            }
        }
    }

    /**
     * Transpose a song from a key to another.
     *
     * @param Chord[] $chords The chords to transpose.
     * @param string $fromKey The target key.
     */
    private function completeTranspose(array $chords, string $fromKey, string $toKey): void
    {
        foreach ($chords as $chord) {
            $suffix = $chord->isMinor() ? 'm' : '';
            $rank = array_search(trim($chord->getRootChord(), 'm'), $this->transposeTable[$this->transposeChords[$fromKey]], true);
            $chord->transposeTo($this->transposeTable[$this->transposeChords[$toKey]][$rank] . $suffix);
        }
    }
}
