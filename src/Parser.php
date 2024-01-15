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
        $split = preg_split('~\R~', $text);
        if ($split !== false) {
            foreach ($split as $line) {
                $line = trim($line);
                switch (substr($line, 0, 1)) {
                    case "{":
                        $lines[] = $this->parseMetadata($line);
                        break;
                    case "#":
                        $lines[] = new Comment(trim(substr($line, 1)));
                        break;
                    case "":
                        $lines[] = new EmptyLine();
                        break;
                    default:
                        $lines[] = $this->parseLyrics($line, $sourceNotations);
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
        $explodedLine = explode('[', $line);
        foreach($explodedLine as $num => $lineFragment) {
            if ($lineFragment !== '') {
                $chordWithText = explode(']', $lineFragment);

                // If the fragment consists of only a chord without text.
                if (isset($chordWithText[1]) && $chordWithText[1] == '') {
                    $blocks[] = new Block(
                        chords: Chord::fromSlice($chordWithText[0], $sourceNotations),
                        text: ''
                    );
                }
                // If first line begins with text and not a chord.
                elseif ($num == 0 && count($chordWithText) == 1) {
                    $blocks[] = new Block(
                        chords: [],
                        text: $chordWithText[0],
                    );
                    // If there is a space after "]", threat it as separate blocks.
                } elseif (substr($chordWithText[1], 0, 1) == " ") {
                    $blocks[] = new Block(
                        chords: Chord::fromSlice($chordWithText[0], $sourceNotations),
                        text: ''
                    );
                    $blocks[] = new Block(
                        chords: [],
                        text: $chordWithText[1]
                    );
                    // If there is no space after "]", threat it as chord with text.
                } else {
                    $blocks[] = new Block(
                        chords: Chord::fromSlice($chordWithText[0], $sourceNotations),
                        text: $chordWithText[1]
                    );
                }
            }
        }
        return new Lyrics($blocks);
    }
}
