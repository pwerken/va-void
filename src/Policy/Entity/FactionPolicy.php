<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use App\Model\Entity\Faction;
use Authorization\IdentityInterface as User;

class FactionPolicy extends EntityPolicy
{
    public function __construct()
    {
        parent::__construct();

        $this->showFieldAuth('characters', ['read-only']);
    }

    public function canView(User $identity, Faction $obj): bool
    {
        return $this->hasAuth(['player'], $obj);
    }

    public function canCharactersIndex(User $identity, Faction $obj): bool
    {
        return $this->hasAuth(['read-only'], $obj);
    }
}
