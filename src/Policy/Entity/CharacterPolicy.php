<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use Authorization\IdentityInterface as User;

use App\Model\Entity\AppEntity;
use App\Model\Entity\Character;

class CharacterPolicy
    extends AppEntityPolicy
{
    public function __construct()
    {
        parent::__construct();

        $this->showFieldAuth('notes',         'read-only');
        $this->showFieldAuth('referee_notes', 'read-only');

        $this->editFieldAuth('player_id',     'infobalie');
        $this->editFieldAuth('chin',          'infobalie');
    }

    public function canEdit(User $identity, Character $obj): bool
    {
        return $this->hasAuth(['referee'], $obj);
    }

    public function canView(User $identity, Character $obj): bool
    {
        return $this->hasAuth(['read-only', 'user'], $obj);
    }

    protected function hasRoleUser(int $plin, AppEntity $obj): bool
    {
        return $obj->get('player_id') == $plin;
    }
}
