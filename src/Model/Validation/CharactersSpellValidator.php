<?php
declare(strict_types=1);

namespace App\Model\Validation;

class CharactersSpellValidator
    extends AppValidator
{
    public function __construct()
    {
        parent::__construct();

        $this->requirePresence('character_id', 'create');
        $this->requirePresence('spell_id', 'create');
        $this->requirePresence('level', 'create');

        $this->nonNegativeInteger('character_id');
        $this->nonNegativeInteger('spell_id');
        $this->nonNegativeInteger('level')->range('level', [1, 3]);
    }
}
