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

    public function canView(User $identity, Item $item)
    {
        return $this->hasAuth(['read-only', 'user'], $item);
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
