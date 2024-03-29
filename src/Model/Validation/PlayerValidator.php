<?php
declare(strict_types=1);

namespace App\Model\Validation;

use App\Model\Entity\Player;

class PlayerValidator
    extends AppValidator
{
    public function __construct()
    {
        parent::__construct();

        $this->requirePresence('id', 'create');
        $this->requirePresence('first_name', 'create');
        $this->requirePresence('last_name', 'create');

        $this->nonNegativeInteger('id');
        $this->inList('role', Player::roleValues());
        $this->allowEmptyString('password');
        $this->notEmptyString('first_name');
        $this->allowEmptyString('insertion');
        $this->notEmptyString('last_name');
        $this->allowEmptyString('email');
        $this->email('email');
    }
}
