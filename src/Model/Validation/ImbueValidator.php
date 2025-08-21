<?php
declare(strict_types=1);

namespace App\Model\Validation;

class ImbueValidator extends Validator
{
    public function __construct()
    {
        parent::__construct();

        $this->requirePresence('name', 'create');
        $this->requirePresence('cost', 'create');
        $this->requirePresence('description', 'create');

        $this->notEmptyString('name');
        $this->integer('cost')->greaterThanOrEqual('cost', 1);
        $this->notEmptyString('description');
        $this->allowEmptyString('notes');
        $this->integer('times_max')->greaterThanOrEqual('times_max', 1);
        $this->allowEmptyString('mana_amount')->integer('mana_amount');
        $this->allowEmptyString('manatype_id')->nonNegativeInteger('manatype_id');
        $this->allowEmptyString('deprecated')->boolean('deprecated');
    }
}
