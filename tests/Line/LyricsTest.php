<?php

declare(strict_types=1);

use ChordPro\Block;
use ChordPro\Chord;
use PHPUnit\Framework\TestCase;
use ChordPro\Line\Lyrics;

final class LyricsTest extends TestCase
{
    public function testClassAccess(): void
    {
        $text = 'This is a test';
        $chord = new Chord('C');
        $block = new Block([$chord], $text);

        $lyrics = new Lyrics([$block], true, true, true);
        $this->assertSame([$block], $lyrics->getBlocks(), 'Blocks are not returned');
        $this->assertTrue($lyrics->hasChords());
        $this->assertTrue($lyrics->hasText());
        $this->assertTrue($lyrics->hasInlineChords());
    }
}
