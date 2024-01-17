<?php

declare(strict_types=1);

use ChordPro\Formatter\JSONFormatter;
use PHPUnit\Framework\TestCase;
use ChordPro\Parser;

final class JSONFormatterTest extends TestCase
{
    public function testWithChords(): void
    {
        $text = file_get_contents(__DIR__ . '/../data/song2.pro');
        $parser = new Parser();
        $song = $parser->parse($text);
        $formatter = new JSONFormatter();
        $json = $formatter->format($song);
        $expected = file_get_contents(__DIR__ . '/../data/song2.json');
        $this->assertSame($expected, $json, 'JSON output is not as expected');
        for ($i = 1; $i <= 11; $i++) {
            $this->assertStringContainsString('Test' . $i, $json);
        }
    }

    public function testWithoutChords(): void
    {
        $text = file_get_contents(__DIR__ . '/../data/song2.pro');
        $parser = new Parser();
        $song = $parser->parse($text);
        $formatter = new JSONFormatter();
        $json = $formatter->format($song, [
            'no_chords' => true
        ]);
        $expected = file_get_contents(__DIR__ . '/../data/song2_no_chords.json');
        $this->assertSame($expected, $json, 'JSON output is not as expected');
        for ($i = 1; $i <= 11; $i++) {
            $this->assertStringContainsString('Test' . $i, $json);
        }
    }

    public function testWithoutMetadata(): void
    {
        $text = file_get_contents(__DIR__ . '/../data/song2.pro');
        $parser = new Parser();
        $song = $parser->parse($text);
        $formatter = new JSONFormatter();
        $json = $formatter->format($song, [
            'ignore_metadata' => ['title']
        ]);
        $expected = file_get_contents(__DIR__ . '/../data/song2_no_metadata.json');
        $this->assertSame($expected, $json, 'JSON output is not as expected');
        for ($i = 1; $i <= 11; $i++) {
            $this->assertStringContainsString('Test' . $i, $json);
        }
    }
}
