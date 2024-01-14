<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use ChordPro\GuessKey;
use ChordPro\Parser;
use PHPUnit\Framework\Attributes\DataProvider;

final class GuessKeyTest extends TestCase
{
    public static function songProvider(): array
    {
        // @todo Provide more examples.
        return [
            ['[C/G] [C/G] [Fmaj7/C] [F(b)] [Fmaj7/C] [F] [G7] [F(b)][C/G] [E7] [F/E] [F(b)] [G] [Fmaj7/C] [G] [Fmaj7/C] [Dm/F] [C/G] [C/G]', 'C'],
            ['[G] [G] [D7] [G] [G] [G] [D7] [G] [D7] [G] [G] [C] [C] [C] [G] [G] [D7] [G7] [D7] [G] [G] [C] [G7] [G] [D7] [D7] [G] [G] [G] [C] [D7] [C] [G] [C]', 'G'],
        ];
    }

    #[DataProvider('songProvider')]
    public function testParse(string $song, string $scale): void
    {
        $parser = new Parser();
        $song = $parser->parse($song);
        $guessKey = new GuessKey();
        $key = $guessKey->guessKey($song);
        $this->assertSame($scale, $key, 'Key is counted incorrectly');
    }
}
