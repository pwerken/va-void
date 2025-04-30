<?php
declare(strict_types=1);

namespace App\Model\Validation;

class ConditionValidator extends Validator
{
    public function __construct()
    {
        parent::__construct();

        $this->requirePresence('name', 'create');
        $this->requirePresence('player_text', 'create');

        $this->nonNegativeInteger('id');
        $this->notEmptyString('name');
        $this->notEmptyString('player_text');
        $this->allowEmptyString('notes');
        $this->allowEmptyString('referee_notes');
        $this->allowEmptyString('deprecated')->boolean('deprecated');
    }
}
