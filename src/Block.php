<?php

declare(strict_types=1);

namespace ChordPro;

use ChordPro\Notation\ChordNotationInterface;

class Block
{
    /**
     * @param Chord[] $chords The chords.
     */
    public function __construct(private array $chords, private string $text, private bool $lineEnd = false)
    {
    }

    /**
     * Get the chords.
     *
     * @return Chord[]
     */
    public function getChords(): array
    {
        return $this->chords;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function isLineEnd(): bool
    {
        return $this->lineEnd;
    }
}
