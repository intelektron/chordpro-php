<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use ChordPro\Block;
use ChordPro\Chord;
use ChordPro\Line\Lyrics;
use ChordPro\Line\Metadata;
use ChordPro\Song;

final class SongTest extends TestCase
{
    public function testSongClassAccess(): void
    {
        $text = 'This is a test';
        $chord = new Chord('C');
        $block = new Block([$chord], $text);

        $lines = [
            new Lyrics([$block]),
            new Metadata('key', 'C'),
        ];
        $song = new Song($lines);

        $this->assertSame($lines, $song->getLines(), 'Lines are not returned');
        $this->assertSame('C', $song->getKey(), 'Key is not returned');
        $song->setKey('D');
        $this->assertSame('D', $song->getKey(), 'Key is not returned after setKey()');
        $this->assertSame('C', $song->getMetadataKey(), 'Metadata key changed.');
    }
}
