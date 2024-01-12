<?php

declare(strict_types=1);

namespace ChordPro\Formatter;

use ChordPro\Notation\ChordNotationInterface;

abstract class Formatter
{
    protected ?ChordNotationInterface $notation;
    protected bool $noChords = false;
    protected array $ignoreMetadata = [];

    public function setOptions(array $options): void
    {
        if (isset($options['notation']) && $options['notation'] instanceof ChordNotationInterface) {
            $this->notation = $options['notation'];
        }
        if (isset($options['no_chords']) && true === $options['no_chords']) {
            $this->noChords = true;
        }
        if (!empty($options['ignore_metadata']) && is_array($options['ignore_metadata'])) {
            $this->ignoreMetadata = $options['ignore_metadata'];
        }
    }
}
