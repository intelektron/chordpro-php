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

        if ($metadata->isSectionStart()) {
            $metadataItem = [
                'type' => 'section_start',
                'sectionType' => $metadata->getSectionType(),
            ];
            if (null !== $metadata->getValue()) {
                $metadataItem['label'] = $metadata->getValue();
            }
        } elseif ($metadata->isSectionEnd()) {
            $metadataItem = [
                'type' => 'section_end',
                'sectionType' => $metadata->getSectionType(),
            ];
        } else {
            $metadataItem = [
                'type' => 'metadata',
                'name' => $metadata->getName(),
                'value' => $metadata->getValue(),
            ];
            if ($metadata->getHumanName() != $metadata->getName()) {
                $metadataItem['humanName'] = $metadata->getHumanName();
            }
        }

        return $metadataItem;
    }

    /**
     * @return mixed[]
     */
    private function getLyricsJSON(Lyrics $lyrics): array
    {
        $return = [];
        foreach ($lyrics->getBlocks() as $block) {
            $chords = [];
            $originalChords = [];
            $slicedChords = $block->getChords();
            foreach ($slicedChords as $slicedChord) {
                if ($slicedChord->isKnown()) {
                    $chords[] = $slicedChord->getRootChord($this->notation).$slicedChord->getExt($this->notation);
                    $originalChords[] = $slicedChord->getRootChord().$slicedChord->getExt();
                } else {
                    $chords[] = $slicedChord->getOriginalName();
                    $originalChords[] = $slicedChord->getOriginalName();
                }
            }
            $chord = implode('/', $chords);
            $originalChord = implode('/', $originalChords);

            $text = $block->getText();
            $blockArray = [];
            if ($text !== '') {
                $blockArray['text'] = rtrim($text);
            }
            $chord = trim($chord);
            if ($chord !== '') {
                $blockArray['chord'] = $chord;
                $blockArray['originalChord'] = $originalChord;
            }
            if ($block->isLineEnd()) {
                $blockArray['lineEnd'] = true;
            }
            $return[] = $blockArray;
        }
        return [
            'type' => $lyrics->hasInlineChords() ? 'line_inline' : 'line',
            'blocks' => $return,
        ];
    }

    /**
     * @return string[]
     */
    private function getLyricsOnlyJSON(Lyrics $lyrics): array
    {
        $text = '';
        foreach ($lyrics->getBlocks() as $block) {
            $text .= ltrim($block->getText());
        }
        $text = rtrim($text);
        if ($text === '') {
            return [];
        } else {
            return [
                'type' => 'line',
                'text' => $text,
            ];
        }
    }
}
