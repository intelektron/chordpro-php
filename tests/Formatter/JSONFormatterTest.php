<?php

declare(strict_types=1);

use ChordPro\Formatter\JSONFormatter;
use PHPUnit\Framework\TestCase;
use ChordPro\Parser;

final class JSONFormatterTest extends TestCase
{
    public function testWithChords(): void
    {
        $text = "{title: Test}\n\n{sov}\n[C7]Test [D]Test2\n{eov}\n# Comment";
        $parser = new Parser();
        $song = $parser->parse($text);
        $formatter = new JSONFormatter();
        $json = $formatter->format($song);

        $result = json_decode($json, true);
        $this->assertSame('metadata', $result[0]['type']);
        $this->assertSame('title', $result[0]['name']);
        $this->assertSame('Test', $result[0]['value']);
        $this->assertSame('empty_line', $result[1]['type']);
        $this->assertSame('metadata', $result[2]['type']);
        $this->assertSame('start_of_verse', $result[2]['name']);
        $this->assertNull($result[2]['value']);
        $this->assertSame('line', $result[3]['type']);
        $this->assertSame(2, count($result[3]['blocks']));
        $this->assertSame('metadata', $result[4]['type']);
        $this->assertSame('end_of_verse', $result[4]['name']);
        $this->assertNull($result[4]['value']);
        $this->assertSame('comment', $result[5]['type']);
        $this->assertSame('Comment', $result[5]['content']);
    }

    public function testWithoutChords(): void
    {
        $text = "{title: Test}\n\n{sov}\n[C7]Test [D]Test2\n{eov}\n# Comment";
        $parser = new Parser();
        $song = $parser->parse($text);
        $formatter = new JSONFormatter();
        $json = $formatter->format($song, [
            'no_chords' => true
        ]);

        $result = json_decode($json, true);
        $this->assertSame('line', $result[3]['type']);
        $this->assertSame('Test Test2', $result[3]['text']);
    }

    public function textWithoutMetadata(): void
    {
        $text = "{title: Test}\n\n{sov}\n[C7]Test [D]Test2\n{eov}\n# Comment";
        $parser = new Parser();
        $song = $parser->parse($text);
        $formatter = new JSONFormatter();
        $json = $formatter->format($song, [
            'ignore_metadata' => ['title']
        ]);

        $result = json_decode($json, true);
        $this->assertSame('empty_line', $result[0]['type']);
    }
}
