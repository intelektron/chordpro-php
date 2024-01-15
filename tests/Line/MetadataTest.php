<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use ChordPro\Line\Metadata;
use PHPUnit\Framework\Attributes\DataProvider;

final class MetadataTest extends TestCase
{
    public function testClassAccess(): void
    {
        $metadata = new Metadata('Test Name', 'Test Value');
        $this->assertEquals('Test Name', $metadata->getName());
        $this->assertEquals('Test Value', $metadata->getValue());

        $metadata2 = new Metadata('Test Name', null);
        $this->assertEquals('Test Name', $metadata2->getName());
        $this->assertNull($metadata2->getValue());
    }

    public static function shortcutProvider(): array
    {
        return [
            ['t', 'title'],
            ['st', 'subtitle'],
            ['c', 'comment'],
            ['ci', 'comment_italic'],
            ['cb', 'comment_box'],
            ['soc', 'start_of_chorus'],
            ['eoc', 'end_of_chorus'],
            ['sov', 'start_of_verse'],
            ['eov', 'end_of_verse'],
            ['sob', 'start_of_bridge'],
            ['eob', 'end_of_bridge'],
            ['sot', 'start_of_tab'],
            ['eot', 'end_of_tab'],
            ['sog', 'start_of_grid'],
            ['eog', 'end_of_grid'],
        ];
    }

    #[DataProvider('shortcutProvider')]
    public function testShortcuts($shortName, $longName): void
    {
        $metadata = new Metadata($shortName, 'Test Value');
        $this->assertEquals($longName, $metadata->getName());

    }
}
