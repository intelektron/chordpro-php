<?php

declare(strict_types=1);

namespace ChordPro\Notation;

use ChordPro\Chord;

abstract class ChordNotation implements ChordNotationInterface
{
    /**
     * Get the table of chord key mappings to English.
     *
     * @return array
     */
    abstract protected function getToEnglishTable(): array;

    /**
     * Get the table of chord key mappings from English.
     *
     * @return array
     */
    abstract protected function getFromEnglishTable(): array;

    public function convertExtToNotation(string $ext): string
    {
        // This will be mostly a no-op.
        return $ext;
    }

    public function convertChordRootToNotation(string $chordRoot): string
    {
        $mappings = $this->getFromEnglishTable();
        return $mappings[$chordRoot] ?? $chordRoot;
    }

    public function convertChordRootFromNotation(string $chordRoot): string
    {
        $mappings = $this->getToEnglishTable();
        return $mappings[$chordRoot] ?? $chordRoot;
    }

}
