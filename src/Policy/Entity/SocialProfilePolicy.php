<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use App\Model\Entity\Entity;
use App\Model\Entity\SocialProfile;
use Authorization\IdentityInterface as User;

class SocialProfilePolicy extends EntityPolicy
{
    public function __construct()
    {
        parent::__construct();

        $this->showFieldAuth('identifier', 'super');
    }

    public function canAdd(User $identity, SocialProfile $obj): bool
    {
        return false;
    }

    public function canView(User $identity, SocialProfile $obj): bool
    {
        return $this->hasAuth(['infobalie', 'user'], $obj);
    }

    public function canEdit(User $identity, SocialProfile $obj): bool
    {
        return false;
    }

    public function canDelete(User $identity, SocialProfile $obj): bool
    {
        return $this->hasAuth(['infobalie', 'user'], $obj);
    }

    protected function hasRoleUser(int $plin, Entity $obj): bool
    {
        return $obj->get('user_id') == $plin;
    }
}
