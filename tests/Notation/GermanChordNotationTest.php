<?php

declare(strict_types=1);

use ChordPro\Formatter\HtmlFormatter;
use ChordPro\Line\Lyrics;
use PHPUnit\Framework\TestCase;
use ChordPro\Notation\GermanChordNotation;
use ChordPro\Parser;
use PHPUnit\Framework\Attributes\DataProvider;

final class GermanChordNotationTest extends TestCase
{
    public static function sourceChordProvider()
    {
        return [
            ['fis', 'F#m'],
            ['cis', 'C#m'],
            ['gis', 'G#m'],
            ['dis', 'D#m'],
            ['eis', 'E#m'],
            ['des', 'Dbm'],
            ['Fes', 'Fb'],
            ['Ces', 'Cb'],
            ['Ges', 'Gb'],
            ['Des', 'Db'],
            ['Fis', 'F#'],
            ['Cis', 'C#'],
            ['Gis', 'G#'],
            ['As', 'Ab'],
            ['Es', 'Eb'],
            ['as', 'Abm'],
            ['es', 'Ebm'],
            ['f', 'Fm'],
            ['c', 'Cm'],
            ['g', 'Gm'],
            ['d', 'Dm'],
            ['a', 'Am'],
            ['e', 'Em'],
            ['h', 'Bm'],
            ['H', 'B'],
            ['b', 'Bbm'],
            ['B', 'Bb'],
            ['F#m', 'F#m'],
            ['C#m', 'C#m'],
            ['G#m', 'G#m'],
            ['D#m', 'D#m'],
            ['A#m', 'A#m'],
            ['E#m', 'E#m'],
            ['Dbm', 'Dbm'],
            ['Abm', 'Abm'],
            ['Ebm', 'Ebm'],
            ['Bbm', 'Bbm'],
            ['Fb', 'Fb'],
            ['Cb', 'Cb'],
            ['Gb', 'Gb'],
            ['Db', 'Db'],
            ['Ab', 'Ab'],
            ['Eb', 'Eb'],
            ['A#', 'A#'],
            ['F#', 'F#'],
            ['C#', 'C#'],
            ['G#', 'G#'],
            ['D#', 'D#'],
            ['Fm', 'Fm'],
            ['Cm', 'Cm'],
            ['Gm', 'Gm'],
            ['Dm', 'Dm'],
            ['Am', 'Am'],
            ['Em', 'Em'],
            ['F', 'F'],
            ['C', 'C'],
            ['G', 'G'],
            ['D', 'D'],
            ['A', 'A'],
            ['E', 'E'],
        ];
    }

    public static function formatChordProvider()
    {
        return [
            ['F#m', 'fis'],
            ['C#m', 'cis'],
            ['G#m', 'gis'],
            ['D#m', 'dis'],
            ['E#m', 'eis'],
            ['Dbm', 'des'],
            ['Fb', 'Fes'],
            ['Cb', 'Ces'],
            ['Gb', 'Ges'],
            ['Db', 'Des'],
            ['F#', 'Fis'],
            ['C#', 'Cis'],
            ['G#', 'Gis'],
            ['Ab', 'As'],
            ['Eb', 'Es'],
            ['Abm', 'as'],
            ['Ebm', 'es'],
            ['Fm', 'f'],
            ['Cm', 'c'],
            ['Gm', 'g'],
            ['Dm', 'd'],
            ['Am', 'a'],
            ['Em', 'e'],
            ['Bm', 'h'],
            ['B', 'H'],
            ['Bbm', 'b'],
            ['Bb', 'B'],
            ['F', 'F'],
            ['C', 'C'],
            ['G', 'G'],
            ['D', 'D'],
            ['A', 'A'],
            ['E', 'E'],
        ];
    }

    #[DataProvider('sourceChordProvider')]
    public function testParse(string $sourceChord, string $targetChord): void
    {
        $text = "[$sourceChord]Test";
        $notation = new GermanChordNotation();
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
        $notation = new GermanChordNotation();
        $parser = new Parser();
        $song = $parser->parse($text);
        $formatter = new HtmlFormatter();
        $html = $formatter->format($song, [
            'notation' => $notation,
        ]);
        $this->assertStringContainsString($targetChord, $html, 'Notation is not formatted correctly');
    }
}
