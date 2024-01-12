<?php

declare(strict_types=1);

namespace ChordPro\Line;

/**
 * A class that represents a metadata line in a song.
 */
class Metadata extends Line
{
    private $name;

    public function __construct(string $name, private ?string $value)
    {
        $this->name = $this->convertToFullName($name);
    }

    public function getName(): string
    {
        return $this->name;
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
            'sot' => 'start_of_grid',
            'eot' => 'end_of_grid',
            default => $name,
        };
    }
}
