<?php

declare(strict_types=1);

use ChordPro\Formatter\HtmlFormatter;
use PHPUnit\Framework\TestCase;
use ChordPro\Parser;

final class HtmlFormatterTest extends TestCase
{
    public function testWithChords(): void
    {
        $text = "{title: Test}\n\n{sov}\n[C7]Test [D]Test2\n{eov}\n# Comment";
        $parser = new Parser();
        $song = $parser->parse($text);
        $formatter = new HtmlFormatter();
        $html = $formatter->format($song);

        $expected = '<div class="chordpro-title">Test</div>' . "\n" .
            '<br />' . "\n" .
            '<div class="chordpro-verse">' . "\n" .
            '<div class="chordpro-line">' . "\n" .
            '<span class="chordpro-block"><span class="chordpro-chord">C<sup>7</sup></span><span class="chordpro-text">Test&nbsp;</span></span><span class="chordpro-block"><span class="chordpro-chord">D</span><span class="chordpro-text">Test2</span></span>' . "\n" .
            '</div>' . "\n" .
            '</div>' . "\n";

        $this->assertSame($expected, $html, 'HTML output is not as expected');
    }

    public function testWithoutChords(): void
    {
        $text = "{title: Test}\n\n{sov}\n[C7]Test [D]Test2\n{eov}\n# Comment";
        $parser = new Parser();
        $song = $parser->parse($text);
        $formatter = new HtmlFormatter();
        $html = $formatter->format($song, [
            'no_chords' => true
        ]);

        $expected = '<div class="chordpro-title">Test</div>' . "\n" .
            '<br />' . "\n" .
            '<div class="chordpro-verse">' . "\n" .
            '<div class="chordpro-line">' . "\n" .
            'Test Test2' . "\n" .
            '</div>' . "\n" .
            '</div>' . "\n";

        $this->assertSame($expected, $html, 'HTML output is not as expected');
    }

    public function testWithoutMetadata(): void
    {
        $text = "{title: Test}\n\n{sov}\n[C7]Test [D]Test2\n{eov}\n# Comment";
        $parser = new Parser();
        $song = $parser->parse($text);
        $formatter = new HtmlFormatter();
        $html = $formatter->format($song, [
            'ignore_metadata' => ['title']
        ]);

        $expected = '<br />' . "\n" .
            '<div class="chordpro-verse">' . "\n" .
            '<div class="chordpro-line">' . "\n" .
            '<span class="chordpro-block"><span class="chordpro-chord">C<sup>7</sup></span><span class="chordpro-text">Test&nbsp;</span></span><span class="chordpro-block"><span class="chordpro-chord">D</span><span class="chordpro-text">Test2</span></span>' . "\n" .
            '</div>' . "\n" .
            '</div>' . "\n";

        $this->assertSame($expected, $html, 'HTML output is not as expected');
    }
}
