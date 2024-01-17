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

        if ($metadata->isSectionStart()) {
            $type = $metadata->getSectionType();
            $content = '';
            if (null !== $metadata->getValue()) {
                $content = '<div class="chordpro-section-label chordpro-'.$type.'-label">'.$metadata->getValue()."</div>\n";
            }
            return $content.'<div class="chordpro-section chordpro-'.$type.'">'."\n";
        } elseif ($metadata->isSectionEnd()) {
            return "</div>\n";
        } else {
            if ($metadata->getName() === 'key' && null != $this->notation) {
                $value = $this->notation->convertChordRootToNotation($metadata->getValue() ?? '');
            } else {
                $value = $metadata->getValue();
            }
            $type = $metadata->getNameSlug();
            $output = '<div class="chordpro-metadata chordpro-'.$type.'">';
            if ($metadata->isNameNecessary()) {
                $output .= '<span class="chordpro-metadata-name">'.$metadata->getHumanName().': </span>';
                $output .= '<span class="chordpro-metadata-value">'.$value.'</span>';
            } else {
                $output .= $value;
            }
            $output .= "</div>\n";
            return $output;
        }
    }

    private function getLyricsHtml(Lyrics $lyrics): string
    {
        $classes = ['chordpro-line'];
        if ($lyrics->hasInlineChords()) {
            $classes[] = 'chordpro-line-inline-chords';
        }
        if (!$lyrics->hasChords()) {
            $classes[] = 'chordpro-line-text-only';
        }
        if (!$lyrics->hasText()) {
            $classes[] = 'chordpro-line-chords-only';
        }
        $line = '<div class="'.implode(' ', $classes).'">'."\n";
        $lineChords = '';
        $lineText = '';
        foreach ($lyrics->getBlocks() as $num => $block) {

            $originalChords = [];
            $chords = [];

            $slicedChords = $block->getChords();
            foreach ($slicedChords as $slicedChord) {
                if ($slicedChord->isKnown()) {
                    $ext = $slicedChord->getExt($this->notation);
                    if ($ext !== '') {
                        $ext = '<sup>'.$ext.'</sup>';
                    }

                    $chords[] = $slicedChord->getRootChord($this->notation).$ext;
                    $originalChords[] = $slicedChord->getRootChord().$slicedChord->getExt();
                } else {
                    $chords[] = $slicedChord->getOriginalName();
                    $originalChords[] = $slicedChord->getOriginalName();
                }
            }

            $chord = implode('/', $chords);
            $originalChord = implode('/', $originalChords);
            $text = $this->blankChars($block->getText());

            if ($lyrics->hasInlineChords()) {
                if ($num === 0) {
                    $lineText = '<div class="chordpro-inline-text">'.$text.'</div>';
                } else {
                    $lineChords .= '<span class="chordpro-chord" data-chord="'.$originalChord.'">'.$chord.'</span>';
                }
            } elseif ($lyrics->hasChords() && $lyrics->hasText()) {
                $line .= '<span class="chordpro-block">' .
                    '<span class="chordpro-chord" data-chord="'.$originalChord.'">'.$chord.'</span>' .
                    '<span class="chordpro-text">'.$text.'</span>' .
                '</span>';
            } elseif ($lyrics->hasChords()) {
                $line .= '<span class="chordpro-block">' .
                  '<span class="chordpro-chord" data-chord="'.$originalChord.'">'.$chord.'</span>' .
                '</span>';

            } elseif ($lyrics->hasText()) {
                $line .= '<span class="chordpro-block">' .
                  '<span class="chordpro-text">'.$text.'</span>' .
                '</span>';
            }
        }

        if ($lyrics->hasInlineChords()) {
            $line .= '<div class="chordpro-inline-block">';
            $line .= '<div class="chordpro-inline-chords">' . $lineChords . '</div>';
            $line .= $lineText;
            $line .= '</div>';
        }

        $line .= "\n</div>\n";
        return $line;
    }

    private function getLyricsOnlyHtml(Lyrics $lyrics): string
    {
        $text = '';
        foreach ($lyrics->getBlocks() as $block) {
            $text .= ltrim($block->getText());
        }
        $text = rtrim($text);
        if ($text === '') {
            return '';
        } else {
            return '<div class="chordpro-line">'."\n".rtrim($text)."\n</div>\n";
        }
    }
}
