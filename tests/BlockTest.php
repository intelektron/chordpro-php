<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use ChordPro\Block;
use ChordPro\Chord;

final class BlockTest extends TestCase
{
    public function testBlockClassAccess(): void
    {
        $text = 'This is a test';
        $chord = new Chord('C');
        $block = new Block([$chord], $text);

        $this->assertSame([$chord], $block->getChords(), 'Chords are not returned');
        $this->assertSame($text, $block->getText(), 'Text is not returned');
    }
}
