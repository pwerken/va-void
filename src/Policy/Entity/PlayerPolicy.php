<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use Authorization\IdentityInterface as User;

use App\Model\Entity\AppEntity;
use App\Model\Entity\Player;

class PlayerPolicy
    extends AppEntityPolicy
{
    public function __construct()
    {
        parent::__construct();

        $this->showFieldAuth('password', ['user', 'infobalie']);
        $this->showFieldAuth('socials',  ['user', 'infobalie']);

        $this->editFieldAuth('role', 'infobalie');
        $this->editFieldAuth('password', ['user', 'infobalie']);
    }

    public function canAdd(User $identity, Player $obj): bool
    {
        return $this->hasAuth(['infobalie'], $obj);
    }

    public function canView(User $identity, Player $obj): bool
    {
        return $this->hasAuth(['read-only', 'user'], $obj);
    }

    public function canEdit(User $identity, Player $obj): bool
    {
        return $this->hasAuth(['infobalie', 'user'], $obj);
    }

    public function canDelete(User $identity, Player $obj): bool
    {
        return $this->canAdd($identity, $obj);
    }

    public function canCharactersIndex(User $identity, Player $obj): bool
    {
        return $this->hasAuth(['read-only', 'user'], $obj);
    }

    protected function hasRoleUser(int $plin, AppEntity $obj): bool
    {
        return $obj->get('id') == $plin;
    }
}
