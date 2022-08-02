<?php
declare(strict_types=1);

namespace App\Policy;

use Authorization\IdentityInterface;

use App\Model\Entity\CharacterCondition;

class CharactersConditionPolicy
    extends AppEntityPolicy
{

    public function __construct()
    {
        parent::__construct();

        $this->showFieldAuth('notes', 'read-only');
        $this->showFieldAuth('referee_notes', 'read-only');
    }

    public function canView(IdentityInterface $identity, CharacterCondition $obj)
    {
        $this->identity = $identity;
        return $this->hasAuth(['read-only', 'user'], $obj);
    }
}
