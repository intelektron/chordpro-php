<?php

declare(strict_types=1);

use ChordPro\Formatter\HtmlFormatter;
use PHPUnit\Framework\TestCase;
use ChordPro\Parser;

final class HtmlFormatterTest extends TestCase
{
    public function testWithChords(): void
    {
        $text = file_get_contents(__DIR__ . '/../data/song2.pro');
        $parser = new Parser();
        $song = $parser->parse($text);
        $formatter = new HtmlFormatter();
        $html = $formatter->format($song);
        $expected = file_get_contents(__DIR__ . '/../data/song2.html');
        $this->assertSame($expected, $html, 'HTML output is not as expected');
        for ($i = 1; $i <= 11; $i++) {
            $this->assertStringContainsString('Test' . $i, $html);
        }
    }

    public function testWithoutChords(): void
    {
        $text = file_get_contents(__DIR__ . '/../data/song2.pro');
        $parser = new Parser();
        $song = $parser->parse($text);
        $formatter = new HtmlFormatter();
        $html = $formatter->format($song, [
            'no_chords' => true
        ]);
        $expected = file_get_contents(__DIR__ . '/../data/song2_no_chords.html');
        $this->assertSame($expected, $html, 'HTML output is not as expected');
        for ($i = 1; $i <= 11; $i++) {
            $this->assertStringContainsString('Test' . $i, $html);
        }
    }

    public function testWithoutMetadata(): void
    {
        $text = file_get_contents(__DIR__ . '/../data/song2.pro');
        $parser = new Parser();
        $song = $parser->parse($text);
        $formatter = new HtmlFormatter();
        $html = $formatter->format($song, [
            'ignore_metadata' => ['title']
        ]);
        $expected = file_get_contents(__DIR__ . '/../data/song2_no_metadata.html');
        $this->assertSame($expected, $html, 'HTML output is not as expected');
        for ($i = 1; $i <= 11; $i++) {
            $this->assertStringContainsString('Test' . $i, $html);
        }
    }
}
