<?php
declare(strict_types=1);

namespace App\Model\Validation;

class ItemValidator extends Validator
{
    public function __construct()
    {
        parent::__construct();

        $this->requirePresence('name', 'create');

        $this->nonNegativeInteger('id');
        $this->notEmptyString('name');
        $this->allowEmptyString('description');
        $this->allowEmptyString('player_text');
        $this->allowEmptyString('referee_notes');
        $this->allowEmptyString('notes');
        $this->allowEmptyString('character_id')->nonNegativeInteger('character_id');
        $this->allowEmptyDate('expiry')->date('expiry');
        $this->allowEmptyString('deprecated')->boolean('deprecated');
    }
}
