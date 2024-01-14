<?php

declare(strict_types=1);

namespace ChordPro\Formatter;

use ChordPro\Song;

interface FormatterInterface
{
    /**
     * @param mixed[] $options
     */
    public function format(Song $song, array $options): string;
}
