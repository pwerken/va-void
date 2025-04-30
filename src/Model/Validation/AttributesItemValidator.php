<?php
declare(strict_types=1);

namespace App\Model\Validation;

class AttributesItemValidator extends Validator
{
    public function __construct()
    {
        parent::__construct();

        $this->requirePresence('attribute_id', 'create');
        $this->requirePresence('item_id', 'create');

        $this->nonNegativeInteger('attribute_id');
        $this->nonNegativeInteger('item_id');
    }
}
