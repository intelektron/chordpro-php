<?php

declare(strict_types=1);

use ChordPro\Formatter\MonospaceFormatter;
use PHPUnit\Framework\TestCase;
use ChordPro\Parser;

final class MonospaceFormatterTest extends TestCase
{
    public function testWithChords(): void
    {
        $text = "{title: Test}\n\n{sov}\n[C7]Test [D]Test2\n{eov}\n# Comment";
        $parser = new Parser();
        $song = $parser->parse($text);
        $formatter = new MonospaceFormatter();
        $monospace = $formatter->format($song);
        $expected = "Test\n\nVERSE\nC7   D    \nTest Test2\n\n";
        $this->assertSame($expected, $monospace, 'Monospace output is not as expected');
    }

    public function testWithoutChords(): void
    {
        $text = "{title: Test}\n\n{sov}\n[C7]Test [D]Test2\n{eov}\n# Comment";
        $parser = new Parser();
        $song = $parser->parse($text);
        $formatter = new MonospaceFormatter();
        $monospace = $formatter->format($song, [
            'no_chords' => true
        ]);
        $expected = "Test\n\nVERSE\nTest Test2\n\n";

        $this->assertSame($expected, $monospace, 'Monospace output is not as expected');
    }

    public function testWithoutMetadata(): void
    {
        $text = "{title: Test}\n\n{sov}\n[C7]Test [D]Test2\n{eov}\n# Comment";
        $parser = new Parser();
        $song = $parser->parse($text);
        $formatter = new MonospaceFormatter();
        $monospace = $formatter->format($song, [
            'ignore_metadata' => ['title']
        ]);
        $expected = "\nVERSE\nC7   D    \nTest Test2\n\n";

        $this->assertSame($expected, $monospace, 'Monospace output is not as expected');
    }
}
