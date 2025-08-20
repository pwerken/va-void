<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use App\Model\Entity\Entity;
use App\Model\Entity\SocialProfile;
use App\Model\Enum\Authorization;
use Authorization\IdentityInterface as User;

class SocialProfilePolicy extends EntityPolicy
{
    public function __construct()
    {
        parent::__construct();

        $this->showFieldAuth('identifier', Authorization::Super);
    }

    public function canAdd(User $identity, SocialProfile $obj): bool
    {
        return false;
    }

    public function canDelete(User $identity, SocialProfile $obj): bool
    {
        return $this->canView($identity, $obj);
    }

    public function canEdit(User $identity, SocialProfile $obj): bool
    {
        return false;
    }

    public function canView(User $identity, SocialProfile $obj): bool
    {
        return $this->hasAuthObj($obj, Authorization::Infobalie, Authorization::Owner);
    }

    protected function hasRoleUser(int $plin, ?Entity $obj): bool
    {
        return $obj?->get('user_id') == $plin;
    }
}
