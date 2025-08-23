<?php
declare(strict_types=1);

namespace App\Model\Validation;

use App\Model\Enum\PlayerRole;

class PlayerValidator extends Validator
{
    public function __construct()
    {
        parent::__construct();

        $this->requirePresence('plin', 'create');
        $this->requirePresence('first_name', 'create');
        $this->requirePresence('last_name', 'create');

        $this->nonNegativeInteger('plin');
        $this->enum('role', PlayerRole::class);
        $this->allowEmptyString('password');
        $this->notEmptyString('first_name');
        $this->allowEmptyString('insertion');
        $this->notEmptyString('last_name');
        $this->allowEmptyString('email');
        $this->email('email');
    }
}
