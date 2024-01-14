<?php

declare(strict_types=1);

namespace ChordPro\Formatter;

use ChordPro\Line\EmptyLine;
use ChordPro\Line\Line;
use ChordPro\Line\Lyrics;
use ChordPro\Line\Metadata;
use ChordPro\Song;

class HtmlFormatter extends Formatter implements FormatterInterface
{
    public function format(Song $song, array $options): string
    {
        $this->setOptions($options);

        $html = '';
        foreach ($song->getLines() as $line) {
            $html .= $this->getLineHtml($line);
        }
        return $html;
    }

    private function getLineHtml(Line $line): string
    {
        if ($line instanceof Metadata) {
            return $this->getMetadataHtml($line);
        } elseif ($line instanceof EmptyLine) {
            return '<br />';
        } elseif ($line instanceof Lyrics) {
            return (true === $this->noChords) ? $this->getLyricsOnlyHtml($line) : $this->getLyricsHtml($line);
        } else {
            return '';
        }
    }

    private function blankChars(?string $text): string
    {
        // @todo Is this if needed?
        if (is_null($text) || $text === '') {
            $text = '&nbsp;';
        }
        return str_replace(' ', '&nbsp;', $text);
    }

    private function getMetadataHtml(Metadata $metadata): string
    {
        // Ignore some metadata.
        if (in_array($metadata->getName(), $this->ignoreMetadata, true)) {
            return '';
        }

        $match = [];
        if (preg_match('/^start_of_(.*)/', $metadata->getName(), $match) !== false) {
            $type = preg_replace('/[\W_\-]/', '', $match[1]);
            $content = '';
            if (null !== $metadata->getValue()) {
                $content = '<div class="chordpro-'.$type.'-comment">'.$metadata->getValue().'</div>';
            }
            return $content.'<div class="chordpro-'.$type.'">';
        } elseif (preg_match('/^end_of_(.*)/', $metadata->getName()) !== false) {
            return '</div>';
        } else {
            $name = preg_replace('/[\W_\-]/', '', mb_strtolower($metadata->getName()));
            return '<div class="chordpro-'.$name.'">'.$metadata->getValue().'</div>';
        }
    }

    private function getLyricsHtml(Lyrics $lyrics): string
    {
        $verse = '<div class="chordpro-verse">';
        foreach ($lyrics->getBlocks() as $block) {

            $chords = [];

            $slicedChords = $block->getChords();
            foreach ($slicedChords as $slicedChord) {
                if ($slicedChord->isKnown()) {
                    $chords[] = $slicedChord->getRootChord($this->notation).'<sup>'.$slicedChord->getExt($this->notation).'</sup>';
                } else {
                    $chords[] = $slicedChord->getOriginalName();
                }
            }

            $chord = implode('/', $chords);
            $text = $this->blankChars($block->getText());

            $verse .= '<span class="chordpro-elem">
              <span class="chordpro-chord">'.$chord.'</span>
              <span class="chordpro-text">'.$text.'</span>
            </span>';
        }
        $verse .= '</div>';
        return $verse;
    }

    private function getLyricsOnlyHtml(Lyrics $lyrics): string
    {
        $verse = '<div class="chordpro-verse">';
        foreach ($lyrics->getBlocks() as $block) {
            $verse .= ltrim($block->getText());
        }
        $verse .= '</div>';
        return $verse;
    }
}
