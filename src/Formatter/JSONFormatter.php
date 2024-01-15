<?php

declare(strict_types=1);

namespace ChordPro\Formatter;

use ChordPro\Line\Comment;
use ChordPro\Line\EmptyLine;
use ChordPro\Line\Line;
use ChordPro\Line\Lyrics;
use ChordPro\Line\Metadata;
use ChordPro\Song;
use PHP_CodeSniffer\Util\Common;

class JSONFormatter extends Formatter implements FormatterInterface
{
    public function format(Song $song, array $options = []): string
    {
        $this->setOptions($options);
        $json = [];

        foreach ($song->getLines() as $line) {
            $jsonLine = $this->getLineJSON($line);
            if (count($jsonLine) > 0) {
                $json[] = $jsonLine;
            }
        }
        return (string) json_encode($json, JSON_PRETTY_PRINT);
    }

    /**
     * @return mixed[]
     */
    private function getLineJSON(Line $line): array
    {
        if ($line instanceof Metadata) {
            return $this->getMetadataJSON($line);
        } elseif ($line instanceof Lyrics) {
            return (true === $this->noChords) ? $this->getLyricsOnlyJSON($line) : $this->getLyricsJSON($line);
        } elseif ($line instanceof EmptyLine) {
            return ['type' => 'empty_line',];
        } elseif ($line instanceof Comment) {
            return ['type' => 'comment', 'content' => $line->getContent()];
        }
        return [];
    }

    /**
     * @return mixed[]
     */
    private function getMetadataJSON(Metadata $metadata): array
    {
        // Ignore some metadata.
        if (in_array($metadata->getName(), $this->ignoreMetadata, true)) {
            return [];
        }
        return [
                'type' => 'metadata',
                'name' => $metadata->getName(),
                'value' => $metadata->getValue(),
        ];
    }

    /**
     * @return mixed[]
     */
    private function getLyricsJSON(Lyrics $lyrics): array
    {
        $return = [];
        foreach ($lyrics->getBlocks() as $block) {
            $chords = [];
            $slicedChords = $block->getChords();
            foreach ($slicedChords as $slicedChord) {
                if ($slicedChord->isKnown()) {
                    $chords[] = $slicedChord->getRootChord($this->notation).$slicedChord->getExt($this->notation);
                } else {
                    $chords[] = $slicedChord->getOriginalName();
                }
            }
            $chord = implode('/', $chords).' ';

            $text = $block->getText();
            $return[] = ['chord' => trim($chord), 'text' => $text];
        }
        return [
            'type' => 'line',
            'blocks' => $return,
        ];
    }

    /**
     * @return string[]
     */
    private function getLyricsOnlyJSON(Lyrics $lyrics): array
    {
        $return = '';
        foreach ($lyrics->getBlocks() as $block) {
            $return .= ltrim($block->getText());
        }
        return [
            'type' => 'line',
            'text' => $return,
        ];
    }
}
