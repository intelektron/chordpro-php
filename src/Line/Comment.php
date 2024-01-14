<?php

declare(strict_types=1);

namespace ChordPro\Line;

/**
 * A class that represents a comment line in a song.
 */
class Comment extends Line
{
    public function __construct(private string $content)
    {
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
