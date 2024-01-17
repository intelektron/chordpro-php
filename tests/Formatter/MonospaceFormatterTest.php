<?php

declare(strict_types=1);

use ChordPro\Formatter\MonospaceFormatter;
use PHPUnit\Framework\TestCase;
use ChordPro\Parser;

final class MonospaceFormatterTest extends TestCase
{
    public function testWithChords(): void
    {
        $text = file_get_contents(__DIR__ . '/../data/song2.pro');
        $parser = new Parser();
        $song = $parser->parse($text);
        $formatter = new MonospaceFormatter();
        $monospace = $formatter->format($song);
        $expected = file_get_contents(__DIR__ . '/../data/song2.txt');
        $this->assertSame($expected, $monospace, 'Monospace output is not as expected');
        for ($i = 1; $i <= 11; $i++) {
            $this->assertStringContainsString('Test' . $i, $monospace);
        }
    }

    public function testWithoutChords(): void
    {
        $text = file_get_contents(__DIR__ . '/../data/song2.pro');
        $parser = new Parser();
        $song = $parser->parse($text);
        $formatter = new MonospaceFormatter();
        $monospace = $formatter->format($song, [
            'no_chords' => true
        ]);
        $expected = file_get_contents(__DIR__ . '/../data/song2_no_chords.txt');
        $this->assertSame($expected, $monospace, 'Monospace output is not as expected');
        for ($i = 1; $i <= 11; $i++) {
            $this->assertStringContainsString('Test' . $i, $monospace);
        }
    }

    public function testWithoutMetadata(): void
    {
        $text = file_get_contents(__DIR__ . '/../data/song2.pro');
        $parser = new Parser();
        $song = $parser->parse($text);
        $formatter = new MonospaceFormatter();
        $monospace = $formatter->format($song, [
            'ignore_metadata' => ['title']
        ]);
        $expected = file_get_contents(__DIR__ . '/../data/song2_no_metadata.txt');
        $this->assertSame($expected, $monospace, 'Monospace output is not as expected');
        for ($i = 1; $i <= 11; $i++) {
            $this->assertStringContainsString('Test' . $i, $monospace);
        }
    }
}
