<?php

declare(strict_types=1);

namespace ChordPro\Formatter;

use ChordPro\Song;

interface FormatterInterface
{
    public function format(Song $song, array $options): string;
}
