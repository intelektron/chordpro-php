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

        $metadata3 = new Metadata('key', 'C');
        $this->assertEquals('Key', $metadata3->getHumanName());
        $this->assertTrue($metadata3->isNameNecessary());
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

    public function testSections(): void {
        $metadata = new Metadata('start_of_Zażółć%_JAŹŃ', 'TEST');
        $this->assertEquals('start_of_Zażółć%_JAŹŃ', $metadata->getName());
        $this->assertEquals('zazolc-jazn', $metadata->getSectionType());
        $this->assertEquals('start-of-zazolc-jazn', $metadata->getNameSlug());
        $this->assertEquals('TEST', $metadata->getValue());
        $this->assertTrue($metadata->isSectionStart());
        $this->assertFalse($metadata->isSectionEnd());

        $metadata = new Metadata('end_of_Zażółć%_JAŹŃ', null);
        $this->assertEquals('end_of_Zażółć%_JAŹŃ', $metadata->getName());
        $this->assertEquals('zazolc-jazn', $metadata->getSectionType());
        $this->assertEquals('end-of-zazolc-jazn', $metadata->getNameSlug());
        $this->assertNull($metadata->getValue());
        $this->assertFalse($metadata->isSectionStart());
        $this->assertTrue($metadata->isSectionEnd());
    }
}
