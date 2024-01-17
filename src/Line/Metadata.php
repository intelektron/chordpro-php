<?php

declare(strict_types=1);

namespace ChordPro\Line;

use PhpParser\Node\Expr\BinaryOp\BooleanOr;

/**
 * A class that represents a metadata line in a song.
 */
class Metadata extends Line
{
    private string $name;

    public function __construct(string $name, private ?string $value)
    {
        $this->name = $this->convertToFullName($name);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNameSlug(): string
    {
        return $this->slugify($this->name);
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * Get the full name of the metadata.
     *
     * ChordPro format allows some shortcuts for the metadata names.
     * This method converts the shortcuts to the full names.
     * The shortcuts are compliant with ChordPro 6.x.
     */
    private function convertToFullName(string $name): string
    {
        return match ($name) {
            't' => 'title',
            'st' => 'subtitle',
            'c' => 'comment',
            'ci' => 'comment_italic',
            'cb' => 'comment_box',
            'soc' => 'start_of_chorus',
            'eoc' => 'end_of_chorus',
            'sov' => 'start_of_verse',
            'eov' => 'end_of_verse',
            'sob' => 'start_of_bridge',
            'eob' => 'end_of_bridge',
            'sot' => 'start_of_tab',
            'eot' => 'end_of_tab',
            'sog' => 'start_of_grid',
            'eog' => 'end_of_grid',
            default => $name,
        };
    }

    /**
     * Get the human readable name of the metadata.
     */
    public function getHumanName(): string
    {
        return match($this->name) {
            'title' => 'Title',
            'subtitle' => 'Subtitle',
            'sorttitle' => 'Sort title',
            'comment' => 'Comment',
            'comment_italic' => 'Comment',
            'comment_box' => 'Comment',
            'key' => 'Key',
            'time' => 'Time',
            'tempo' => 'Tempo',
            'duration' => 'Duration',
            'capo' => 'Capo',
            'artist' => 'Artist',
            'composer' => 'Composer',
            'lyricist' => 'Lyricist',
            'album' => 'Album',
            'year' => 'Year',
            'copyright' => 'Copyright',
            'meta' => 'Meta',
            default => $this->name,
        };
    }

    public function isNameNecessary(): bool
    {
        return match($this->name) {
            'title' => false,
            'subtitle' => false,
            'comment' => false,
            'comment_italic' => false,
            'comment_box' => false,
            'artist' => false,
            default => true,
        };
    }

    public function isSectionStart(): bool
    {
        return str_starts_with($this->name, 'start_of_');
    }

    public function isSectionEnd(): bool
    {
        return str_starts_with($this->name, 'end_of_');
    }

    public function getSectionType(): string
    {
        $match = [];
        if (preg_match('/^start_of_(.*)/', $this->name, $match) === 1) {
            return $this->slugify($match[1]);
        } elseif (preg_match('/^end_of_(.*)/', $this->name, $match) === 1) {
            return $this->slugify($match[1]);
        } else {
            return '';
        }
    }

    private function slugify(string $text): string
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', (string) $text);
        $text = preg_replace('~[^-\w]+~', '', (string) $text);
        $text = trim((string) $text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower((string) $text);

        if ($text === '') {
            return 'n-a';
        }
        return $text;
    }

}
