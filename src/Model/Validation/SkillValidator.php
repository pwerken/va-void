<?php
declare(strict_types=1);

namespace App\Model\Validation;

class SkillValidator extends Validator
{
    public function __construct()
    {
        parent::__construct();

        $this->requirePresence('name', 'create');
        $this->requirePresence('cost', 'create');

        $this->nonNegativeInteger('id');
        $this->notEmptyString('name');
        $this->integer('cost')->greaterThanOrEqual('times', 1);
        $this->nonNegativeInteger('manatype_id')->allowEmptyString('manatype_id');
        $this->nonNegativeInteger('mana_amount');
        $this->nonNegativeInteger('sort_order');
    }
}
