<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use App\Model\Entity\Entity;
use App\Model\Entity\Item;
use Authorization\IdentityInterface as User;
use Cake\ORM\Locator\LocatorAwareTrait;

class ItemPolicy extends EntityPolicy
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

    public function canView(User $identity, Item $obj): bool
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

    protected function hasRoleUser(int $plin, ?Entity $obj): bool
    {
        $char_id = $obj?->get('character_id');
        if ($char_id === null) {
            return false;
        }

        $char = $this->getTableLocator()->get('Characters')->get($char_id);

        return $char->get('player_id') == $plin;
    }
}
