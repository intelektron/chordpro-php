<?php

declare(strict_types=1);

namespace ChordPro;

use ChordPro\Line\Comment;
use ChordPro\Line\EmptyLine;
use ChordPro\Line\Lyrics;
use ChordPro\Line\Metadata;
use ChordPro\Notation\ChordNotationInterface;

class Parser
{
    /**
     * Parse the song text.
     *
     * @param string $text The song text to parse.
     * @param ChordNotationInterface[] $sourceNotations The notations to use, ordered by precedence.
     *
     * @return Song
     */
    public function parse(string $text, array $sourceNotations = []): Song
    {
        $lines = [];
        $split = preg_split("/\\r\\n|\\r|\\n/", $text);
        if ($split !== false) {
            foreach ($split as $line) {
                $line = preg_replace('/^\s+/', '', $line);
                $line = preg_replace('/\s+$/', '', (string) $line);

                switch (substr((string) $line, 0, 1)) {
                    case "{":
                        $lines[] = $this->parseMetadata((string) $line);
                        break;
                    case "#":
                        $lines[] = new Comment(trim(substr((string) $line, 1)));
                        break;
                    case "":
                        $lines[] = new EmptyLine();
                        break;
                    default:
                        $lines[] = $this->parseLyrics((string) $line, $sourceNotations);
                }
            }
        }

        return new Song($lines);
    }

    /**
     * Parse a song line, assuming it contains metadata.
     *
     * The metadata is defined inside curly braces.
     * It can either contain {name: value}, or just {name}.
     *
     * @param string $line A line of the song.
     * @return \ChordPro\Line\Metadata The structured metadata.
     */
    private function parseMetadata(string $line): Metadata
    {
        $line = trim($line, "{}");
        $pos = strpos($line, ":");

        if ($pos !== false) {
            $name = trim(substr($line, 0, $pos));
            $value = trim(substr($line, $pos + 1));
        } else {
            $name = $line;
            $value = null;
        }

        return new Metadata($name, $value);
    }

    /**
     * Parse a song line, assuming it contains lyrics.
     *
     * @param string $line A line of the song.
     * @param ChordNotationInterface[] $sourceNotations The notations to use, ordered by precedence.
     *
     * @return \ChordPro\Line\Lyrics The structured lyrics
     */
    private function parseLyrics(string $line, array $sourceNotations = []): Lyrics
    {
        $blocks = [];
        $possiblyEmptyBlocks = [];

        $hasText = false;
        $hasChords = false;

        // First, check for ~ symbol.
        $match = [];
        if (preg_match('/^([^\[\]]+)~(.+)/', $line, $match) === 1) {
            $matchChords = [];
            $lineText = $match[1];
            $result = preg_match_all('/\[([^\[\]]+)\]/', $match[2], $matchChords);
            if (is_numeric($result) && $result > 0) {
                $lineChords = $matchChords[1];
                $blocks[] = new Block(
                    chords: [],
                    text: trim($lineText)
                );
                foreach ($lineChords as $chord) {
                    $blocks[] = new Block(
                        chords: Chord::fromSlice($chord, $sourceNotations),
                        text: '',
                        lineEnd: true
                    );
                }
                return new Lyrics(
                    blocks: $blocks,
                    hasInlineChords: true,
                    hasChords: true,
                    hasText: true,
                );
            }

        }

        $explodedLine = explode('[', $line);
        foreach($explodedLine as $num => $lineFragment) {
            if ($lineFragment !== '') {
                $chordWithText = explode(']', $lineFragment);

                // If the fragment consists of only a chord without text.
                if (isset($chordWithText[1]) && $chordWithText[1] == '') {
                    $hasChords = true;
                    $blocks[] = new Block(
                        chords: Chord::fromSlice($chordWithText[0], $sourceNotations),
                        text: ''
                    );
                }
                // If first block begins with text and not a chord.
                elseif ($num == 0 && count($chordWithText) == 1) {
                    $blocks[] = new Block(
                        chords: [],
                        text: $chordWithText[0],
                    );
                    if (preg_match('/\S/', $chordWithText[0])  === 1) {
                        $hasText = true;
                    } else {
                        // Save the block as possibly empty, so we can remove it later.
                        $possiblyEmptyBlocks[] = array_key_last($blocks);
                    }

                } elseif (isset($chordWithText[1]) && substr($chordWithText[1], 0, 1) == " ") {
                    // If there is a space after "]", threat it as two separate blocks.
                    $hasChords = true;
                    $blocks[] = new Block(
                        chords: Chord::fromSlice($chordWithText[0], $sourceNotations),
                        text: ''
                    );
                    $blocks[] = new Block(
                        chords: [],
                        text: $chordWithText[1]
                    );
                    if (preg_match('/\S/', $chordWithText[1])  === 1) {
                        $hasText = true;
                    } else {
                        // Save the block as possibly empty, so we can remove it later.
                        $possiblyEmptyBlocks[] = array_key_last($blocks);
                    }

                } else {
                    // If there is no space after "]", threat it as chord with text.
                    $hasChords = true;
                    $blocks[] = new Block(
                        chords: Chord::fromSlice($chordWithText[0], $sourceNotations),
                        text: $chordWithText[1] ?? ''
                    );
                    if (preg_match('/\S/', $chordWithText[1] ?? '')  === 1) {
                        $hasText = true;
                    } else {
                        // Save the block as possibly empty, so we can remove it later.
                        $possiblyEmptyBlocks[] = array_key_last($blocks);
                    }

                }
            }
        }

        // If there are only chords and no text, set text to empty string.
        if ($hasChords && !$hasText) {
            foreach ($possiblyEmptyBlocks as $blockKey) {
                unset($blocks[$blockKey]);
            }
        } elseif (!$hasChords && !$hasText) {
            $blocks = [];
        }

        return new Lyrics(
            blocks: $blocks,
            hasInlineChords: false,
            hasChords: $hasChords,
            hasText: $hasText,
        );
    }
}
