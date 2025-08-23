<?php
declare(strict_types=1);

namespace App\Model\Validation;

use App\Model\Enum\CharacterStatus;

class CharacterValidator extends Validator
{
    public function __construct()
    {
        parent::__construct();

        $this->requirePresence('plin', 'create');
        $this->requirePresence('chin', 'create');
        $this->requirePresence('name', 'create');

        $this->nonNegativeInteger('id');
        $this->nonNegativeInteger('plin');
        $this->nonNegativeInteger('chin');
        $this->notEmptyString('name');
        $this->numeric('xp')->regex('xp', '/^\d+([.,](0|25|5|75)0*)?$/');
        $this->nonNegativeInteger('faction_id');
        $this->allowEmptyString('belief');
        $this->allowEmptyString('group');
        $this->allowEmptyString('world');
        $this->enum('status', CharacterStatus::class);
    }
}
