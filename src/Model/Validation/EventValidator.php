<?php
declare(strict_types=1);

namespace App\Model\Validation;

class EventValidator extends Validator
{
    public function __construct()
    {
        parent::__construct();

        $this->requirePresence('name', 'create');

        $this->nonNegativeInteger('id');
        $this->notEmptyString('name');
    }
}
