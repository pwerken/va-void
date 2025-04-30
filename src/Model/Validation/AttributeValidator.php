<?php
declare(strict_types=1);

namespace App\Model\Validation;

class AttributeValidator extends Validator
{
    public function __construct()
    {
        parent::__construct();

        $this->requirePresence('id', 'create');
        $this->requirePresence('name', 'create');
        $this->requirePresence('code', 'create');

        $this->nonNegativeInteger('id');
        $this->notEmptyString('name');
        $this->allowEmptyString('category');
        $this->notEmptyString('code')
                ->minLength('code', 2)
                ->maxLength('code', 2);
    }
}
