<?php
declare(strict_types=1);

namespace App\Model\Validation;

use App\Model\Enum\LammyStatus;

class LammyValidator extends Validator
{
    public function __construct()
    {
        parent::__construct();

        $this->requirePresence('entity', 'create');
        $this->requirePresence('key1', 'create');

        $this->nonNegativeInteger('id');
        $this->notEmptyString('entity');
        $this->nonNegativeInteger('key1');
        $this->allowEmptyString('key2')->nonNegativeInteger('key2');
        $this->enum('status', LammyStatus::class);
    }
}
