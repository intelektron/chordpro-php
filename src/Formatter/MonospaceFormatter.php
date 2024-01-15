<?php

declare(strict_types=1);

namespace ChordPro\Formatter;

use ChordPro\Line\EmptyLine;
use ChordPro\Line\Line;
use ChordPro\Line\Lyrics;
use ChordPro\Line\Metadata;
use ChordPro\Song;

class MonospaceFormatter extends Formatter implements FormatterInterface
{
    public function format(Song $song, array $options = []): string
    {
        $this->setOptions($options);

        $monospace = '';
        foreach ($song->getLines() as $line) {
            $monospace .= $this->getLineMonospace($line);
        }
        return $monospace;
    }

    private function getLineMonospace(Line $line): string
    {
        if ($line instanceof Metadata) {
            return $this->getMetadataMonospace($line);
        } elseif ($line instanceof EmptyLine) {
            return "\n";
        } elseif ($line instanceof Lyrics) {
            return (true === $this->noChords) ? $this->getLyricsOnlyMonospace($line) : $this->getLyricsMonospace($line);
        } else {
            return '';
        }
    }

    private function getMetadataMonospace(Metadata $metadata): string
    {
        // Ignore some metadata.
        if (in_array($metadata->getName(), $this->ignoreMetadata, true)) {
            return '';
        }

        $match = [];
        if (preg_match('/^start_of_(.*)/', $metadata->getName(), $match) === 1) {
            $content = (null !== $metadata->getValue()) ? $metadata->getValue()."\n" : mb_strtoupper($match[1]) . "\n";
            return $content;
        } elseif (preg_match('/^end_of_(.*)/', $metadata->getName()) === 1) {
            return "\n";
        } else {
            return $metadata->getValue()."\n";
        }
    }

    private function generateBlank(int $count): string
    {
        $i = 1;
        $blank = '';
        if ($count >= 1) {
            while ($i <= $count) {
                $blank .= ' ';
                $i++;
            }
        }
        return $blank;
    }

    private function getLyricsMonospace(Lyrics $lyrics): string
    {
        $lineChords = '';
        $lineTexts = '';

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

            $chord = implode('/', $chords);
            $text = $block->getText();

            if (mb_strlen($text) < mb_strlen($chord)) {
                $text = $text.$this->generateBlank(mb_strlen($chord) - mb_strlen($text));
            }

            $lineChords .= $chord.$this->generateBlank(mb_strlen($text) - mb_strlen($chord));
            $lineTexts .= $text;
        }

        return $lineChords."\n".$lineTexts."\n";
    }

    private function getLyricsOnlyMonospace(Lyrics $lyrics): string
    {
        $texts = '';
        foreach ($lyrics->getBlocks() as $block) {
            $texts .= ltrim($block->getText());
        }
        return $texts."\n";
    }
}
