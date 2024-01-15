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
    public function format(Song $song, array $options = []): string
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
            return "<br />\n";
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
        if (preg_match('/^start_of_(.*)/', $metadata->getName(), $match) === 1) {
            $type = preg_replace('/[\W_\-]/', '', $match[1]);
            $content = '';
            if (null !== $metadata->getValue()) {
                $content = '<div class="chordpro-'.$type.'-comment">'.$metadata->getValue()."</div>\n";
            }
            return $content.'<div class="chordpro-'.$type.'">'."\n";
        } elseif (preg_match('/^end_of_(.*)/', $metadata->getName()) === 1) {
            return "</div>\n";
        } else {
            $name = preg_replace('/[\W_\-]/', '', mb_strtolower($metadata->getName()));
            return '<div class="chordpro-'.$name.'">'.$metadata->getValue()."</div>\n";
        }
    }

    private function getLyricsHtml(Lyrics $lyrics): string
    {
        $line = '<div class="chordpro-line">'."\n";
        foreach ($lyrics->getBlocks() as $block) {

            $chords = [];

            $slicedChords = $block->getChords();
            foreach ($slicedChords as $slicedChord) {
                if ($slicedChord->isKnown()) {
                    $ext = $slicedChord->getExt();
                    if ($ext !== '') {
                        $ext = '<sup>'.$ext.'</sup>';
                    }
                    $chords[] = $slicedChord->getRootChord($this->notation).$ext;
                } else {
                    $chords[] = $slicedChord->getOriginalName();
                }
            }

            $chord = implode('/', $chords);
            $text = $this->blankChars($block->getText());

            $line .= '<span class="chordpro-block">' .
              '<span class="chordpro-chord">'.$chord.'</span>' .
              '<span class="chordpro-text">'.$text.'</span>' .
            '</span>';
        }
        $line .= "\n</div>\n";
        return $line;
    }

    private function getLyricsOnlyHtml(Lyrics $lyrics): string
    {
        $line = '<div class="chordpro-line">'."\n";
        foreach ($lyrics->getBlocks() as $block) {
            $line .= ltrim($block->getText());
        }
        $line .= "\n</div>\n";
        return $line;
    }
}
