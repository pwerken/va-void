<?php
declare(strict_types=1);

namespace App\Model\Validation;

class CharactersPowerValidator extends Validator
{
    public function __construct()
    {
        parent::__construct();

        $this->requirePresence('character_id', 'create');
        $this->requirePresence('power_id', 'create');

        $this->nonNegativeInteger('character_id');
        $this->nonNegativeInteger('power_id');
        $this->allowEmptyDate('expiry')->date('expiry');
    }
}
