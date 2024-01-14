<?php

declare(strict_types=1);

namespace ChordPro;

use ChordPro\Notation\ChordNotationInterface;

/**
 * A class for chord manipulations.
 */
class Chord
{
    public const ROOT_CHORDS = ['F#m', 'C#m', 'G#m', 'D#m', 'A#m', 'E#m', 'Dbm', 'Abm', 'Ebm', 'Bbm', 'Fb', 'Cb', 'Gb', 'Db', 'Ab', 'Eb', 'Bb', 'A#', 'F#', 'C#', 'G#', 'D#', 'Fm', 'Cm', 'Gm', 'Dm', 'Am', 'Em', 'Bm', 'F', 'C', 'G', 'D', 'A', 'E', 'B'];

    /**
     * The main chord.
     */
    private string $rootChord = '';

    /**
     * The extension of the chord.
     */
    private string $ext = '';

    /**
     * Was the chord recognized?
     */
    private bool $isKnown = false;

    /**
     * Static cache of different notation roots.
     */
    private static $notationRoots = [];

    public function __construct(private string $originalName, ?ChordNotationInterface $sourceNotation = null)
    {
        $rootChordTable = $this->getRootChordTable($sourceNotation);

        foreach ($rootChordTable as $rootChord) {
            if (str_starts_with($originalName, $rootChord)) {
                $this->rootChord = $rootChord;
                $this->ext = substr($originalName, strlen($rootChord));
                $this->isKnown = true;
                break;
            }
        }
        if (!isset($this->rootChord)) {
            $this->isKnown = false;
        }
    }

    public static function fromSlice(string $text, ?ChordNotationInterface $notation = null): array
    {
        if (empty($text)) {
            return [];
        }
        $chords = explode('/', $text);
        $result = [];
        foreach ($chords as $chord) {
            $result[] = new Chord($chord, $notation);
        }
        return $result;
    }

    public function isKnown(): bool
    {
        return $this->isKnown;
    }

    public function isMinor(): bool
    {
        return substr($this->rootChord, -1) === 'm';
    }

    private function getRootChordTable(?ChordNotationInterface $notation): array
    {
        if (!$notation) {
            return self::ROOT_CHORDS;
        } elseif (isset($this->notationRoots[$notation::class])) {
            return $this->notationRoots[$notation::class];
        } else {
            $rootChordTable = [];
            foreach (self::ROOT_CHORDS as $rootChord) {
                $rootChordTable[] = $notation->convertChordRootFromNotation($rootChord);
            }
            $this->notationRoots[$notation::class] = $rootChordTable;
            return $rootChordTable;
        }
    }

    public function getRootChord(?ChordNotationInterface $targetNotation = null): string
    {
        if ($targetNotation) {
            return $targetNotation->convertChordRootToNotation($this->rootChord);
        }
        return $this->rootChord;
    }

    public function getExt(?ChordNotationInterface $targetNotation = null): string
    {
        if ($targetNotation) {
            return $targetNotation->convertExtToNotation($this->ext);
        }
        return $this->ext;
    }

    public function getOriginalName(): string
    {
        return $this->originalName;
    }

    public function transposeTo(string $rootChord): void
    {
        $this->rootChord = $rootChord;
    }

}
