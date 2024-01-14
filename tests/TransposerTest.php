<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use ChordPro\Line\Lyrics;
use ChordPro\Parser;
use ChordPro\Transposer;
use PHPUnit\Framework\Attributes\DataProvider;

final class TransposerTest extends TestCase
{
    public static function transposedChordProvider(): array
    {
        return [
            // +2
            ['C', 2, 'D'],
            ['C#', 2, 'D#'],
            ['Db', 2, 'D#'],
            ['D', 2, 'E'],
            ['Eb', 2, 'F'],
            ['D#', 2, 'F'],
            ['E', 2, 'F#'],
            ['F', 2, 'G'],
            ['F#', 2, 'G#'],
            ['Gb', 2, 'G#'],
            ['G', 2, 'A'],
            ['Ab', 2, 'A#'],
            ['G#', 2, 'A#'],
            ['A', 2, 'B'],
            ['Bb', 2, 'C'],
            ['A#', 2, 'C'],
            ['B', 2, 'C#'],
            // -4
            ['C', -4, 'G#'],
            ['C#', -4, 'A'],
            ['Db', -4, 'A'],
            ['D', -4, 'A#'],
            ['Eb', -4, 'B'],
            ['D#', -4, 'B'],
            ['E', -4, 'C'],
            ['F', -4, 'C#'],
            ['F#', -4, 'D'],
            ['Gb', -4, 'D'],
            ['G', -4, 'D#'],
            ['Ab', -4, 'E'],
            ['G#', -4, 'E'],
            ['A', -4, 'F'],
            ['Bb', -4, 'F#'],
            ['A#', -4, 'F#'],
            ['B', -4, 'G'],
            // To 'D'
            ['C', 'D', 'D'],
            ['C#', 'D', 'D#'],
            ['Db', 'D', 'Eb'],
            ['D', 'D', 'E'],
            ['Eb', 'D', 'F'],
            ['D#', 'D', 'E#'],
            ['E', 'D', 'F#'],
            ['F', 'D', 'G'],
            ['F#', 'D', 'G#'],
            ['Gb', 'D', 'Ab'],
            ['G', 'D', 'A'],
            ['Ab', 'D', 'Bb'],
            ['G#', 'D', 'A#'],
            ['A', 'D', 'B'],
            ['Bb', 'D', 'C'],
            ['A#', 'D', 'B#'],
            ['B', 'D', 'C#'],
            // Minors
            ['Cm', 2, 'Dm'],
            ['C#m', 2, 'D#m'],
            ['Dbm', 2, 'D#m'],
            ['Cm', -4, 'G#m'],
            ['C#m', -4, 'Am'],
            ['Dbm', -4, 'Am'],
            ['Cm', 'D', 'Dm'],
            ['C#m', 'D', 'D#m'],
            ['Dbm', 'D', 'Ebm'],
        ];
    }

    #[DataProvider('transposedChordProvider')]
    public function testTransposer($chord, $target, $newChord): void
    {
        $parser = new Parser();
        $transposer = new Transposer();
        $song = $parser->parse("[$chord]test");
        $song->setKey('C');
        $transposer->transpose($song, $target);
        $line = $song->getLines()[0];
        assert($line instanceof Lyrics);
        $this->assertSame($newChord, $line->getBlocks()[0]->getChords()[0]->getRootChord());
    }
}
