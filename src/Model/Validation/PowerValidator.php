<?php
declare(strict_types=1);

namespace App\Model\Validation;

class PowerValidator
    extends AppValidator
{
    public function __construct()
    {
        parent::__construct();

        $this->requirePresence('name', 'create');
        $this->requirePresence('player_text', 'create');

        $this->nonNegativeInteger('id');
        $this->notEmptyString('name');
        $this->notEmptyString('player_text');
        $this->allowEmptyString('notes');
        $this->allowEmptyString('referee_notes');
        $this->allowEmpty('deprecated')->boolean('deprecated');
    }
}
