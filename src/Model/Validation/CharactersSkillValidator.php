<?php
declare(strict_types=1);

namespace App\Model\Validation;

class CharactersSkillValidator extends Validator
{
    public function __construct()
    {
        parent::__construct();

        $this->requirePresence('character_id', 'create');
        $this->requirePresence('skill_id', 'create');

        $this->nonNegativeInteger('character_id');
        $this->nonNegativeInteger('skill_id');
        $this->integer('times')->greaterThanOrEqual('times', 1);
    }
}
