<?php

declare(strict_types=1);

namespace ChordPro\Formatter;

use ChordPro\Notation\ChordNotationInterface;

abstract class Formatter
{
    protected ?ChordNotationInterface $notation = null;
    protected bool $noChords = false;

    /**
     * @var string[]
     */
    protected array $ignoreMetadata = [];

    /**
     * @param mixed[] $options
     */
    public function setOptions(array $options): void
    {
        $this->notation = null;
        $this->noChords = false;
        $this->ignoreMetadata = [];

        if (isset($options['notation']) && $options['notation'] instanceof ChordNotationInterface) {
            $this->notation = $options['notation'];
        }
        if (isset($options['no_chords']) && true === $options['no_chords']) {
            $this->noChords = true;
        }
        if (isset($options['ignore_metadata']) && is_array($options['ignore_metadata'])) {
            $this->ignoreMetadata = $options['ignore_metadata'];
        }
    }
}
