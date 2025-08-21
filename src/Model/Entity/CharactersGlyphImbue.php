<?php
declare(strict_types=1);

namespace App\Model\Entity;

class CharactersGlyphImbue extends CharactersImbue
{
    protected array $_defaults = [
        'type' => 'glyph',
    ];

    public function __construct(array $properties = [], array $options = [])
    {
        parent::__construct($properties, $options);

        $this->setCompact(['times', 'character', 'imbue'], true);
    }
}
