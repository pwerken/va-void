<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use Authorization\IdentityInterface as User;
use Cake\ORM\Locator\LocatorAwareTrait;

use App\Model\Entity\AppEntity;
use App\Model\Entity\Item;

class ItemPolicy
    extends AppEntityPolicy
{
    use LocatorAwareTrait;

    public function __construct()
    {
        parent::__construct();

        $this->showFieldAuth('attributes', ['read-only']);
        $this->showFieldAuth('notes', ['read-only']);
        $this->showFieldAuth('referee_notes', ['read-only']);
    }

    public function canAdd(User $identity, Item $obj): bool
    {
        return $this->hasAuth(['referee'], $obj);
    }

    public function canView(User $identity, Item $obj)
    {
        return $this->hasAuth(['read-only', 'user'], $obj);
    }

    public function canEdit(User $identity, Item $obj): bool
    {
        return $this->canAdd($identity, $obj);
    }

    public function canDelete(User $identity, Item $obj): bool
    {
        return $this->canAdd($identity, $obj);
    }

    protected function hasRoleUser(int $plin, AppEntity $obj): bool
    {
        $char_id = $obj->character_id;
        if ($char_id === null)
            return false;

        $char = $this->getTableLocator()->get('Characters')->get($char_id);
        return $char->player_id == $plin;
    }
}
