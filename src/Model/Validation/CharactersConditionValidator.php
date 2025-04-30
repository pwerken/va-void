<?php
declare(strict_types=1);

namespace App\Model\Validation;

class CharactersConditionValidator extends Validator
{
    public function __construct()
    {
        parent::__construct();

        $this->requirePresence('character_id', 'create');
        $this->requirePresence('condition_id', 'create');

        $this->nonNegativeInteger('character_id');
        $this->nonNegativeInteger('condition_id');
        $this->allowEmptyDate('expiry')->date('expiry');
    }
}
