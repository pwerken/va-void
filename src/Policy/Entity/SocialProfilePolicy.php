<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use Authorization\IdentityInterface as User;

use App\Model\Entity\AppEntity;
use App\Model\Entity\SocialProfile;

class SocialProfilePolicy
    extends AppEntityPolicy
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

    protected function hasRoleUser(int $plin, AppEntity $obj): bool
    {
        return $obj->get('user_id') == $plin;
    }
}
