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

        $lines = [];
        foreach ($song->getLines() as $line) {
            $lines[] = $this->getLineMonospace($line);
        }

        $this->transformInlineChords($lines);
        return implode("", $lines);
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

        if ($metadata->isSectionStart()) {
            $type = $metadata->getSectionType();
            $content = (null !== $metadata->getValue()) ? mb_strtoupper($metadata->getValue())."\n" : mb_strtoupper($type) . "\n";
            return $content;
        } elseif ($metadata->isSectionEnd()) {
            return '';
        } else {
            if ($metadata->isNameNecessary()) {
                return $metadata->getHumanName().': '.$metadata->getValue()."\n";
            } else {
                return $metadata->getValue()."\n";
            }
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
        $lineChords = [];
        $lineChordsWithBlanks = '';
        $lineTexts = '';
        $lineTextsWithBlanks = '';

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
            $textWithBlanks = $text;

            if (mb_strlen($text) < mb_strlen($chord)) {
                $textWithBlanks = $text.$this->generateBlank(mb_strlen($chord) - mb_strlen($text));
            }

            $lineChordsWithBlanks .= $chord.$this->generateBlank(mb_strlen($text) - mb_strlen($chord));
            $lineChords[] = $chord;
            $lineTexts .= $text;
            $lineTextsWithBlanks .= $textWithBlanks;
        }

        $output = '';
        if ($lyrics->hasInlineChords()) {
            $output .= '~'.$lineTexts."~".implode(' ', $lineChords)."\n";
        } else {
            if ($lyrics->hasChords() && $lyrics->hasText()) {
                $output .= $lineChordsWithBlanks."\n";
                $output .= $lineTextsWithBlanks."\n";
            } elseif ($lyrics->hasChords()) {
                $output .= implode(' ', $lineChords)."\n";
            } elseif ($lyrics->hasText()) {
                $output .= $lineTexts."\n";
            }
        }
        return $output;
    }

    /**
     * @param string[] $lines
     */
    private function transformInlineChords(array &$lines): void
    {
        $linesToFix = [];
        $longest = 0;
        foreach ($lines as $num => $line) {
            $match = [];
            if (preg_match('/^~(.+)~(.+)/', $line, $match) === 1) {
                $linesToFix[$num] = [
                    'text' => trim($match[1]),
                    'chords' => trim($match[2]),
                ];
                if (mb_strlen($match[1]) > $longest) {
                    $longest = mb_strlen($match[1]);
                }
            }
        }

        $inlineChordPosition = $longest + 4;
        foreach ($linesToFix as $num => $lineToFix) {
            $lines[$num] = $lineToFix['text'].$this->generateBlank($inlineChordPosition - mb_strlen($lineToFix['text']));
            $lines[$num] .= $lineToFix['chords']."\n";
        }
    }

    private function getLyricsOnlyMonospace(Lyrics $lyrics): string
    {
        $texts = '';
        foreach ($lyrics->getBlocks() as $block) {
            $texts .= ltrim($block->getText());
        }
        return ($texts !== '') ? rtrim($texts)."\n" : '';
    }
}
