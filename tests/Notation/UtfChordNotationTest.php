<?php

declare(strict_types=1);

use ChordPro\Formatter\HtmlFormatter;
use ChordPro\Line\Lyrics;
use PHPUnit\Framework\TestCase;
use ChordPro\Notation\UtfChordNotation;
use ChordPro\Parser;
use PHPUnit\Framework\Attributes\DataProvider;

final class UtfChordNotationTest extends TestCase
{
    public static function sourceChordProvider()
    {
        return [
            ['A♭m', 'Abm'],
            ['B♭m', 'Bbm'],
            ['D♭m', 'Dbm'],
            ['E♭m', 'Ebm'],
            ['A♯m', 'A#m'],
            ['C♯m', 'C#m'],
            ['D♯m', 'D#m'],
            ['E♯m', 'E#m'],
            ['F♯m', 'F#m'],
            ['G♯m', 'G#m'],
            ['A♭', 'Ab'],
            ['B♭', 'Bb'],
            ['C♭', 'Cb'],
            ['D♭', 'Db'],
            ['E♭', 'Eb'],
            ['F♭', 'Fb'],
            ['G♭', 'Gb'],
            ['A♯', 'A#'],
            ['C♯', 'C#'],
            ['D♯', 'D#'],
            ['F♯', 'F#'],
            ['G♯', 'G#'],
        ];
    }

    public static function formatChordProvider()
    {
        return [
            ['Abm', 'A♭m'],
            ['Bbm', 'B♭m'],
            ['Dbm', 'D♭m'],
            ['Ebm', 'E♭m'],
            ['A#m', 'A♯m'],
            ['C#m', 'C♯m'],
            ['D#m', 'D♯m'],
            ['E#m', 'E♯m'],
            ['F#m', 'F♯m'],
            ['G#m', 'G♯m'],
            ['Ab', 'A♭'],
            ['Bb', 'B♭'],
            ['Cb', 'C♭'],
            ['Db', 'D♭'],
            ['Eb', 'E♭'],
            ['Fb', 'F♭'],
            ['Gb', 'G♭'],
            ['A#', 'A♯'],
            ['C#', 'C♯'],
            ['D#', 'D♯'],
            ['F#', 'F♯'],
            ['G#', 'G♯'],
        ];
    }

    #[DataProvider('sourceChordProvider')]
    public function testParse(string $sourceChord, string $targetChord): void
    {
        $text = "[$sourceChord]Test";
        $notation = new UtfChordNotation();
        $parser = new Parser();
        $song = $parser->parse($text, [$notation]);
        $lines = $song->getLines();
        $firstLine = $lines[0];
        assert($firstLine instanceof Lyrics);
        $this->assertSame($targetChord, $firstLine->getBlocks()[0]->getChords()[0]->getRootChord(), 'Notation is not parsed correctly');
    }

    #[DataProvider('formatChordProvider')]
    public function testFormat(string $sourceChord, string $targetChord): void
    {
        $text = "[$sourceChord]Test";
        $notation = new UtfChordNotation();
        $parser = new Parser();
        $song = $parser->parse($text);
        $formatter = new HtmlFormatter();
        $html = $formatter->format($song, [
            'notation' => $notation,
        ]);
        $this->assertStringContainsString($targetChord, $html, 'Notation is not formatted correctly');
    }
}
