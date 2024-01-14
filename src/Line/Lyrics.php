<?php

declare(strict_types=1);

namespace ChordPro\Line;

/**
 * A class that represents a lyrics line in a song.
 */
class Lyrics extends Line
{
    /**
     * @param \ChordPro\Block[] $blocks The blocks of the line.
     */
    public function __construct(private array $blocks)
    {
    }

    /**
     * Get all the blocks of the line.
     *
     * @return \ChordPro\Block[]
     */
    public function getBlocks(): array
    {
        return $this->blocks;
    }
}
