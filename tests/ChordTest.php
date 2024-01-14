<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use ChordPro\Chord;
use PHPUnit\Framework\Attributes\DataProvider;

final class ChordTest extends TestCase
{
    public static function chordProvider(): array
    {
        return [
            ['Zzzzz', false, false, '', ''],
            ['C', true, false, 'C', ''],
            ['C7', true, false, 'C', '7'],
            ['Am', true, true, 'Am', ''],
            ['Am7', true, true, 'Am', '7'],
            ['F#', true, false, 'F#', ''],
            ['Bb', true, false, 'Bb', ''],
            ['F#7', true, false, 'F#', '7'],
            ['F#m7', true, true, 'F#m', '7'],
        ];
    }

    #[DataProvider('chordProvider')]
    public function testParse(string $originalName, bool $isKnown, bool $isMinor, string $rootChord, string $ext): void
    {
        $chord = new Chord($originalName);
        $this->assertSame($isKnown, $chord->isKnown(), 'Chord\'s "known" status parsed incorrectly');
        $this->assertSame($isMinor, $chord->isMinor(), 'Chord\'s "minor" status parsed incorrectly');
        $this->assertSame($rootChord, $chord->getRootChord(), 'Chord\'s root parsed incorrectly');
        $this->assertSame($ext, $chord->getExt(), 'Chord\'s ext parsed incorrectly');
    }

    public function testSlice()
    {
        $chords = Chord::fromSlice('C/Am/F#m7');
        $this->assertCount(3, $chords, 'The number of chords parsed incorrectly');
        $this->assertSame('C', $chords[0]->getRootChord(), 'The first chord parsed incorrectly');
        $this->assertSame('Am', $chords[1]->getRootChord(), 'The second chord parsed incorrectly');
        $this->assertSame('F#m', $chords[2]->getRootChord(), 'The third chord parsed incorrectly');
        $this->assertSame('7', $chords[2]->getExt(), 'The third chord\'s ext parsed incorrectly');
    }

    public function testTranspose()
    {
        $chord = new Chord('C7');
        $chord->transposeTo('D');
        $this->assertSame('D', $chord->getRootChord(), 'The chord root parsed incorrectly after transpose');
        $this->assertSame('7', $chord->getExt(), 'The ext parsed incorrectly after transpose');
    }
}
