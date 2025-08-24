<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use App\Model\Entity\Entity;
use App\Model\Entity\Item;
use App\Model\Enum\Authorization;
use Authorization\IdentityInterface as User;

class ItemPolicy extends EntityPolicy
{
    public function __construct()
    {
        parent::__construct();

        $this->showFieldAuth('attributes', Authorization::ReadOnly);
        $this->showFieldAuth('notes', Authorization::ReadOnly);
        $this->showFieldAuth('referee_notes', Authorization::ReadOnly);
    }

    public function canAdd(User $identity, Item $obj): bool
    {
        return $this->hasAuthObj($obj, Authorization::Referee);
    }

    public function canDelete(User $identity, Item $obj): bool
    {
        return $this->hasAuthObj($obj, Authorization::Super);
    }

    public function canEdit(User $identity, Item $obj): bool
    {
        return $this->canAdd($identity, $obj);
    }

    public function canView(User $identity, Item $obj): bool
    {
        return $this->hasAuthObj($obj, Authorization::ReadOnly, Authorization::Owner);
    }

    protected function hasRoleUser(int $plin, ?Entity $obj): bool
    {
        $char_id = $obj?->get('character_id');
        if (is_null($char_id)) {
            return false;
        }

        $char = $this->fetchTable('Characters')->get($char_id);

        return $char->get('plin') == $plin;
    }
}
