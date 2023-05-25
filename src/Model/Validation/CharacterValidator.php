<?php
declare(strict_types=1);

namespace App\Model\Validation;

use Cake\Routing\Router;

use App\Model\Entity\Character;

class CharacterValidator
    extends AppValidator
{
    public function __construct()
    {
        parent::__construct();

        $this->requirePresence('player_id', 'create');
        $this->requirePresence('chin', 'create');
        $this->requirePresence('name', 'create');

        $this->nonNegativeInteger('id');
        $this->nonNegativeInteger('player_id');
        $this->naturalNumber('chin');
        $this->notEmptyString('name');
        $this->numeric('xp')->regex('xp', '/^\d+([.,](0|25|5|75)0*)?$/');
        $this->nonNegativeInteger('faction_id');
        $this->nonNegativeInteger('belief_id');
        $this->nonNegativeInteger('group_id');
        $this->nonNegativeInteger('world_id');
        $this->allowEmptyString('soulpath')->inList('soulpath', Character::soulpathValues());
        $this->inList('status', Character::statusValues());
    }
}
