<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use App\Model\Entity\Entity;
use App\Model\Entity\Player;
use App\Model\Enum\Authorization;
use Authorization\IdentityInterface as User;

class PlayerPolicy extends EntityPolicy
{
    public function __construct()
    {
        parent::__construct();

        $this->showFieldAuth('password', Authorization::Infobalie, Authorization::Owner);
        $this->showFieldAuth('socials', Authorization::Infobalie, Authorization::Owner);

        $this->editFieldAuth('role', Authorization::Referee);
        $this->editFieldAuth('password', Authorization::Infobalie, Authorization::Owner);
    }

    public function canAdd(User $identity, Player $obj): bool
    {
        return $this->hasAuthObj($obj, Authorization::Infobalie);
    }

    public function canDelete(User $identity, Player $obj): bool
    {
        return $this->canAdd($identity, $obj);
    }

    public function canEdit(User $identity, Player $obj): bool
    {
        return $this->hasAuthObj($obj, Authorization::Infobalie, Authorization::Owner);
    }

    public function canView(User $identity, Player $obj): bool
    {
        return $this->hasAuthObj($obj, Authorization::ReadOnly, Authorization::Owner);
    }

    public function canCharactersIndex(User $identity, Player $obj): bool
    {
        return $this->canView($identity, $obj);
    }

    protected function hasRoleUser(int $plin, ?Entity $obj): bool
    {
        return $obj?->get('plin') == $plin;
    }
}
