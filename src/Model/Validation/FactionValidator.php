<?php
declare(strict_types=1);

namespace App\Model\Validation;

class FactionValidator extends Validator
{
    public function __construct()
    {
        parent::__construct();

        $this->requirePresence('name', 'create');

        $this->nonNegativeInteger('id');
        $this->notEmptyString('name');
    }
}
