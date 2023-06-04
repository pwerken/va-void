<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use Authorization\IdentityInterface as User;

use App\Model\Entity\World;

class WorldPolicy
    extends AppEntityPolicy
{
    public function __construct()
    {
        parent::__construct();

        $this->showFieldAuth('characters', ['read-only']);
    }

    public function canAdd(User $identity, World $obj)
    {
        return $this->hasAuth(['infobalie'], $obj);
    }

    public function canView(User $identity, World $obj)
    {
        return $this->hasAuth(['player'], $obj);
    }

    public function canEdit(User $identity, World $obj)
    {
        return $this->canAdd($identity, $obj);
    }

    public function canDelete(User $identity, World $obj)
    {
        return $this->canAdd($identity, $obj);
    }

    public function canCharactersIndex(User $identity, World $obj)
    {
        return $this->hasAuth(['read-only'], $obj);
    }
}
