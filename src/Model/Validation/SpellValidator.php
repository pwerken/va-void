<?php
declare(strict_types=1);

namespace App\Model\Validation;

class SpellValidator
    extends AppValidator
{
    public function __construct()
    {
        parent::__construct();

        $this->requirePresence('name', 'create');
        $this->requirePresence('short', 'create');
        $this->requirePresence('spiritual', 'create');

        $this->nonNegativeInteger('id');
        $this->notEmptyString('name');
        $this->notEmptyString('short');
        $this->boolean('spiritual');
    }
}
