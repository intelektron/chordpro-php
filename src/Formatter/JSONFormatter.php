<?php

declare(strict_types=1);

namespace ChordPro\Formatter;

use ChordPro\Line\Line;
use ChordPro\Line\Lyrics;
use ChordPro\Line\Metadata;
use ChordPro\Song;

class JSONFormatter extends Formatter implements FormatterInterface
{
    public function format(Song $song, array $options): string
    {
        $this->setOptions($options);
        $json = [];

        foreach ($song->getLines() as $line) {
            $json[] = $this->getLineJSON($line);
        }
        return json_encode($json, JSON_PRETTY_PRINT);
    }


    private function getLineJSON(Line $line)
    {
        if ($line instanceof Metadata) {
            return $this->getMetadataJSON($line);
        } elseif ($line instanceof Lyrics) {
            return (true === $this->noChords) ? $this->getLyricsOnlyJSON($line) : $this->getLyricsJSON($line);
        } else {
            return null;
        }
    }

    private function getMetadataJSON(Metadata $metadata): ?array
    {
        // Ignore some metadata.
        if (in_array($metadata->getName(), $this->ignoreMetadata)) {
            return null;
        }

        if (empty($metadata->getValue())) {
            return [$metadata->getName()];
        } else {
            switch($metadata->getName()) {
                default:
                    return [$metadata->getName() => $metadata->getValue()];
            }
        }
    }

    private function getLyricsJSON(Lyrics $lyrics): array
    {
        $return = [];
        foreach ($lyrics->getBlocks() as $block) {
            $chords = [];
            $slicedChords = $block->getChords();
            if (!empty($slicedChords)) {
                foreach ($slicedChords as $slicedChord) {
                    if ($slicedChord->isKnown()) {
                        $chords[] = $slicedChord->getRootChord($this->notation).$slicedChord->getExt($this->notation);
                    } else {
                        $chords[] = $slicedChord->getOriginalName();
                    }
                }
            }
            $chord = implode('/', array_map("implode", $chords)).' ';

            $text = $block->getText();
            $return[] = ['chord' => trim($chord), 'text' => $text];
        }
        return $return;
    }

    private function getLyricsOnlyJSON(Lyrics $lyrics): string
    {
        $return = '';
        foreach ($lyrics->getBlocks() as $block) {
            $return .= ltrim($block->getText());
        }
        return $return;
    }
}
