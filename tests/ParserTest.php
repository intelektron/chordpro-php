<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use ChordPro\Line\Comment;
use ChordPro\Line\EmptyLine;
use ChordPro\Line\Lyrics;
use ChordPro\Line\Metadata;
use ChordPro\Parser;

final class ParserTest extends TestCase
{
    public function testParse(): void
    {
        $text = file_get_contents(__DIR__ . '/data/song1.pro');
        $parser = new Parser();
        $song = $parser->parse($text);

        foreach ($song->getLines() as $key => $line) {
            switch ($key) {
                case 0: // Comment.
                    assert($line instanceof Comment);
                    $this->assertSame('A sample song', $line->getContent());
                    break;

                case 1: // Empty line.
                    assert($line instanceof EmptyLine);
                    break;

                case 2: // Metadata - title.
                    assert($line instanceof Metadata);
                    $this->assertSame('title', $line->getName());
                    $this->assertSame('Sample Song', $line->getValue());
                    break;

                case 3: // Metadata - subtitle.
                    assert($line instanceof Metadata);
                    $this->assertSame('subtitle', $line->getName());
                    $this->assertSame('Sing it along', $line->getValue());
                    break;

                case 4: // Metadata - key.
                    assert($line instanceof Metadata);
                    $this->assertSame('key', $line->getName());
                    $this->assertSame('D', $line->getValue());
                    break;

                case 5: // Metadata - composer.
                    assert($line instanceof Metadata);
                    $this->assertSame('composer', $line->getName());
                    $this->assertSame('John Doe', $line->getValue());
                    break;

                case 6: // Metadata - comment.
                    assert($line instanceof Metadata);
                    $this->assertSame('comment', $line->getName());
                    $this->assertSame('Don\'t try to play it!', $line->getValue());
                    break;

                case 7: // Metadata - comment.
                    assert($line instanceof Metadata);
                    $this->assertSame('comment', $line->getName());
                    $this->assertSame('Seriously!', $line->getValue());
                    break;

                case 8: // Empty line.
                    assert($line instanceof EmptyLine);
                    break;

                case 9: // Metadata - start of chorus.
                    assert($line instanceof Metadata);
                    $this->assertSame('start_of_chorus', $line->getName());
                    $this->assertSame('Refrain', $line->getValue());
                    break;

                case 10: // Lyrics - first line.
                    assert($line instanceof Lyrics);
                    $this->assertCount(4, $line->getBlocks());

                    // Sing it [D]loud, sing it [G7/E]cle[A#m]ar,

                    $this->assertSame('Sing it ', $line->getBlocks()[0]->getText());
                    $this->assertCount(0, $line->getBlocks()[0]->getChords());

                    $this->assertSame('loud, sing it ', $line->getBlocks()[1]->getText());
                    $this->assertCount(1, $line->getBlocks()[1]->getChords());
                    $this->assertSame('D', $line->getBlocks()[1]->getChords()[0]->getRootChord());
                    $this->assertSame('', $line->getBlocks()[1]->getChords()[0]->getExt());

                    $this->assertSame('cle', $line->getBlocks()[2]->getText());
                    $this->assertCount(2, $line->getBlocks()[2]->getChords());
                    $this->assertSame('G', $line->getBlocks()[2]->getChords()[0]->getRootChord());
                    $this->assertSame('7', $line->getBlocks()[2]->getChords()[0]->getExt());
                    $this->assertSame('E', $line->getBlocks()[2]->getChords()[1]->getRootChord());
                    $this->assertSame('', $line->getBlocks()[2]->getChords()[1]->getExt());

                    $this->assertSame('ar,', $line->getBlocks()[3]->getText());
                    $this->assertCount(1, $line->getBlocks()[3]->getChords());
                    $this->assertSame('A#m', $line->getBlocks()[3]->getChords()[0]->getRootChord());
                    $this->assertSame('', $line->getBlocks()[3]->getChords()[0]->getExt());
                    break;

                case 11: // Metadata - end of chorus.
                    assert($line instanceof Metadata);
                    $this->assertSame('end_of_chorus', $line->getName());
                    $this->assertNull($line->getValue());
                    break;

                case 12: // Empty line.
                    assert($line instanceof EmptyLine);
                    break;

                case 13: // Metadata - start of verse.
                    assert($line instanceof Metadata);
                    $this->assertSame('start_of_verse', $line->getName());
                    $this->assertNull($line->getValue());
                    break;

                case 14: // Lyrics - first line.
                    assert($line instanceof Lyrics);
                    $this->assertCount(3, $line->getBlocks());

                    // [A]These lyrics are [Dm] a nightmare.

                    $this->assertSame('These lyrics are ', $line->getBlocks()[0]->getText());
                    $this->assertCount(1, $line->getBlocks()[0]->getChords());
                    $this->assertSame('A', $line->getBlocks()[0]->getChords()[0]->getRootChord());
                    $this->assertSame('', $line->getBlocks()[0]->getChords()[0]->getExt());

                    $this->assertSame('', $line->getBlocks()[1]->getText());
                    $this->assertCount(1, $line->getBlocks()[1]->getChords());
                    $this->assertSame('Dm', $line->getBlocks()[1]->getChords()[0]->getRootChord());
                    $this->assertSame('', $line->getBlocks()[1]->getChords()[0]->getExt());

                    $this->assertSame(' a nightmare.', $line->getBlocks()[2]->getText());
                    $this->assertCount(0, $line->getBlocks()[2]->getChords());
                    break;

                case 15: // Metadata - end of verse.
                    assert($line instanceof Metadata);
                    $this->assertSame('end_of_verse', $line->getName());
                    $this->assertNull($line->getValue());
                    break;
            }
        }
    }
}
