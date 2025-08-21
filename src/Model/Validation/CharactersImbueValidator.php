<?php
declare(strict_types=1);

namespace App\Model\Validation;

abstract class CharactersImbueValidator extends Validator
{
    public function __construct()
    {
        parent::__construct();

        $this->requirePresence('imbue_id', 'create');
        $this->requirePresence('character_id', 'create');

        $this->nonNegativeInteger('imbue_id');
        $this->nonNegativeInteger('character_id');
        $this->integer('times')->greaterThanOrEqual('times', 1);
    }
}
