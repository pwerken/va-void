<?php
declare(strict_types=1);

namespace App\Model\Validation;

class HistoryValidator
    extends AppValidator
{
    public function __construct()
    {
        parent::__construct();

        $this->requirePresence('entity', 'create');
        $this->requirePresence('key1', 'create');

        $this->notEmptyString('entity');
        $this->nonNegativeInteger('key1');
        $this->nonNegativeInteger('key2')->allowEmpty('key2');
    }
}
