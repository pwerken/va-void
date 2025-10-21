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
        $this->integer('base_max')->greaterThanOrEqual('base_max', 0);
        $this->integer('times_max')->greaterThanOrEqual('times_max', 1);
        $this->integer('cost')->greaterThanOrEqual('cost', 1);
        $this->nonNegativeInteger('manatype_id')->allowEmptyString('manatype_id');
        $this->nonNegativeInteger('mana_amount')->allowEmptyString('mana_amount');
        $this->boolean('loresheet');
        $this->boolean('blanks');
        $this->boolean('deprecated');
        $this->nonNegativeInteger('sort_order');
    }
}
