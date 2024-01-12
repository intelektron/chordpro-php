<?php

declare(strict_types=1);

namespace ChordPro;

use ChordPro\Line\Metadata;

class Song extends \ArrayObject
{
    private $key;

    /**
     * Song constructor.
     *
     * @param \ChordPro\Line\Line[] $lines The lines of the song.
     */
    public function __construct(private array $lines)
    {
    }

    /**
     * Get the key defined by the metadata.
     *
     * @return string|null The key, or null if not defined.
     */
    public function getMetadataKey(): ?string
    {
        foreach ($this->lines as $line) {
            if ($line instanceof Metadata and $line->getName() == 'key') {
                return $line->getValue();
            }
        }

        return null;
    }

    /**
     * Get the key of the song.
     *
     * First, look for the key defined by setKey().
     * If not defined, look for the key defined by the metadata.
     *
     * @return string|null The key, or null if not defined.
     */
    public function getKey()
    {
        return $this->key ?: $this->getMetadataKey();
    }

    public function setKey($value)
    {
        $this->key = $value;
    }

    /**
     * @return \ChordPro\Line\Line[] The lines of the song.
     */
    public function getLines(): array
    {
        return $this->lines;
    }

}
