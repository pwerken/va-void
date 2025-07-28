<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use App\Model\Entity\Entity;
use App\Model\Entity\Player;
use Authorization\IdentityInterface as User;

class PlayerPolicy extends EntityPolicy
{
    public function __construct()
    {
        parent::__construct();

        $this->showFieldAuth('password', ['user', 'infobalie']);
        $this->showFieldAuth('socials', ['user', 'infobalie']);

        $this->editFieldAuth('role', 'referee');
        $this->editFieldAuth('password', ['user', 'infobalie']);
    }

    public function canAdd(User $identity, Player $obj): bool
    {
        return $this->hasAuth(['infobalie'], $obj);
    }

    public function canDelete(User $identity, Player $obj): bool
    {
        return $this->canAdd($identity, $obj);
    }

    public function canEdit(User $identity, Player $obj): bool
    {
        return $this->hasAuth(['infobalie', 'user'], $obj);
    }

    public function canView(User $identity, Player $obj): bool
    {
        return $this->hasAuth(['read-only', 'user'], $obj);
    }

    public function canCharactersIndex(User $identity, Player $obj): bool
    {
        return $this->hasAuth(['read-only', 'user'], $obj);
    }

    protected function hasRoleUser(int $plin, ?Entity $obj): bool
    {
        return $obj?->get('id') == $plin;
    }
}
